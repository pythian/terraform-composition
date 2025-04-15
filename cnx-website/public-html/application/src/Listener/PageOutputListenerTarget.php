<?php
namespace Application\Src\Listener;

use Exception;
use Throwable;
use Concrete\Core\Page\Page;
use Concrete\Core\User\User;
use Concrete\Core\Http\Request;
use Concrete\Core\Permission\Checker;
use Concrete\Core\Application\ApplicationAwareTrait;
use Concrete\Core\Application\ApplicationAwareInterface;
use Concrete\Core\Logging\Logger;
use Psr\Log\LoggerInterface;

final class PageOutputListenerTarget implements ApplicationAwareInterface
{
    use ApplicationAwareTrait;

    protected $request;
    protected $logger;

    public function __construct(Request $request, LoggerInterface $logger)
    {
        $this->request = $request;
        $logger = $logger->withName('target_log');
        $this->logger = $logger;
    }

    /**
     * @param \Symfony\Component\EventDispatcher\GenericEvent $event the event object sent by the on_page_output event
     */
    public function handle($event)
    {
        $contents = $event->getArgument('contents');
        /** @var Page $page */
        $page = Page::getCurrentPage();
        $contents = $this->processPage($contents, $page);
        $event->setArgument('contents', $contents);

        return $event;
    }


    /**
     * Call the main process to manage the whole optimization process.
     *
     * @param string $contents the HTML content sent over by the on_page_output event
     * @param \Concrete\Core\Page\Page $page the current page
     *
     * @return string the content optimized
     */
    private function processPage($contents, $page)
    {
        $contents=str_replace('target="_blank"','target="_blank" rel="noopener noreferrer"',$contents);

        return $contents;
    }
}
