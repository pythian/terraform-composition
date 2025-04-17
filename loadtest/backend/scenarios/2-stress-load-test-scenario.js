/*
data.csv file must be replaced by the real data file on the server

- expected csv headers:
uri
- csv must not contain empty lines
*/

import http from 'k6/http';
import { check, sleep } from 'k6';
import { Trend, Rate } from 'k6/metrics';
import { SharedArray } from 'k6/data';
import { scenario } from 'k6/execution';
import papaparse from 'https://jslib.k6.io/papaparse/5.1.1/index.js';

const baseURL = 'dev.connexusenergy.com'
const getErrorRate = new Rate('get_errors');
const getTrend = new Trend('get_response_time');

const csvData = new SharedArray('data', function () {
    return papaparse.parse(open('./data.csv'), { header: true }).data;
});

export const options = {
    scenarios: {
        'stress_test': {
            executor: 'shared-iterations',
            iterations: csvData.length,
            vus: 100,
            maxDuration: '30m',
        },
    },
    thresholds: {
        get_errors: ['rate<0.01'], // < 1%
        get_response_time: ['p(95)<2000'], // < 2s
    },
};

export default function () {
    const urlGet = `https://${baseURL}`;
    const params = {
        headers: {
            'accept': 'application/vnd.pythian+json; version=3; profile=https://schema.pythian.com/v3/anything.json',
            'Accept-Encoding': Math.random() > 0.68 ? 'gzip' : 'br',
            'Content-Type': 'application/json',
        }
    };

    var additionalQueryParams = ''
    var getRequest = {
        'get': {
            'url': `${urlGet}${csvData[scenario.iterationInTest].uri}${additionalQueryParams}`,
            'params': params,
        },
    };

    const getResp = http.get(getRequest.get.url, getRequest.get.params);

    check(getResp, {
        'status is 200': (r) => r.status === 200
    }) || getErrorRate.add(1);

    getTrend.add(getResp.timings.duration);

    sleep(1);
}
