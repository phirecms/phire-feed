<?php
/**
 * Phire Feed Module
 *
 * @link       https://github.com/phirecms/phire-feed
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2016 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.phirecms.org/license     New BSD License
 */

/**
 * @namespace
 */
namespace Phire\Feed\Controller;

use Phire\Feed\Model;
use Phire\Controller\AbstractController;

/**
 * Feed Index Controller class
 *
 * @category   Phire\Feed
 * @package    Phire\Feed
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2016 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.phirecms.org/license     New BSD License
 * @version    1.0.0
 */
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
