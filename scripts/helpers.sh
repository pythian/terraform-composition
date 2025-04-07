# parse access logs based on number of requests
awk '$9 == 200 && $6 ~ /GET/ && $7 !~ /\.[a-zA-Z]{2,4}$/ {print $7}' connexusenergy.com-ssl_log-Feb-2025 | sort | uniq -c | awk '{print $2","$1}' | sort -t, -k2 -nr > uris.txt
# shuffle 1% of the top 300 pages requested in a file
head -n 300 uris.txt | awk -F',' '{for(i=1; i<=($2*0.01); i++) print $1}' | shuf > finallist.txt
# the file could be used as data.csv
