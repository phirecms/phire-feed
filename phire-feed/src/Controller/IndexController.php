<?php

namespace Phire\Feed\Controller;

use Phire\Feed\Model;
use Phire\Controller\AbstractController;

class IndexController extends AbstractController
{

    /**
     * Feed action method
     *
     * @return void
     */
    public function feed()
    {
        $feed   = new Model\Feed();
        $writer = $feed->getFeed(
            $this->application->module('phire-feed')['feed_headers'],
            $this->application->module('phire-feed')['feed_type'],
            $this->application->module('phire-feed')['feed_limit'],
            $this->application->modules()
        );

        echo $writer;
    }

}
