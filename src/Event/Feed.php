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
namespace Phire\Feed\Event;

use Phire\Feed\Model;
use Phire\Feed\Table;
use Pop\Application;
use Phire\Controller\AbstractController;

/**
 * Feed Event class
 *
 * @category   Phire\Feed
 * @package    Phire\Feed
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2016 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.phirecms.org/license     New BSD License
 * @version    1.0.0
 */
class Feed
{

    /**
     * Bootstrap the module
     *
     * @param  Application $application
     * @return void
     */
    public static function bootstrap(Application $application)
    {
        $forms    = $application->config()['forms'];

        if (isset($forms['Phire\Content\Form\Content'])) {
            $forms['Phire\Content\Form\Content'][0]['feed'] = [
                'type'  => 'radio',
                'label' => 'Include in Feed?',
                'value' => [
                    '1' => 'Yes',
                    '0' => 'No'
                ],
                'marked' => '0'
            ];
            $forms['Phire\Content\Form\Content'][0]['feed_type'] = [
                'type'  => 'hidden',
                'value' => 'content'
            ];
        }

        if (isset($forms['Phire\Media\Form\Media'])) {
            $forms['Phire\Media\Form\Media'][0]['feed'] = [
                'type'  => 'radio',
                'label' => 'Include in Feed?',
                'value' => [
                    '1' => 'Yes',
                    '0' => 'No'
                ],
                'marked' => '0'
            ];
            $forms['Phire\Media\Form\Media'][0]['feed_type'] = [
                'type'  => 'hidden',
                'value' => 'media'
            ];
        }

        $application->mergeConfig(['forms' => $forms], true);
    }


    /**
     * Get feed value for form object
     *
     * @param  AbstractController $controller
     * @param  Application        $application
     * @return void
     */
    public static function getAll(AbstractController $controller, Application $application)
    {
        if ((!$_POST) && ($controller->hasView()) && (null !== $controller->view()->form) && ($controller->view()->form !== false) &&
            ((int)$controller->view()->form->id != 0) && (null !== $controller->view()->form) &&
            ($controller->view()->form instanceof \Pop\Form\Form)) {
            $type = $controller->view()->form->feed_type;
            $id   = $controller->view()->form->id;

            if (null !== $type) {
                $feed = Table\Feed::findById([$id, $type]);
                if (isset($feed->id)) {
                    $controller->view()->form->feed = '1';
                }
            }
        }
    }

    /**
     * Save feed relationships
     *
     * @param  AbstractController $controller
     * @param  Application        $application
     * @return void
     */
    public static function save(AbstractController $controller, Application $application)
    {
        if (($_POST) && ($controller->hasView()) && (null !== $controller->view()->id) &&
            (null !== $controller->view()->form) && ($controller->view()->form instanceof \Pop\Form\Form)) {
            $id   = $controller->view()->id;
            $feed = $controller->view()->form->feed;
            $type = $controller->view()->form->feed_type;
            if ((null !== $feed) && (null !== $type)) {
                if ($feed == '1') {
                    $f = Table\Feed::findById([$id, $type]);
                    if (!isset($f->id)) {
                        $feed = new Table\Feed([
                            'id'   => $id,
                            'type' => $type
                        ]);
                        $feed->save();
                    }
                } else if ($feed == '0') {
                    $f = Table\Feed::findById([$id, $type]);
                    if (isset($f->id)) {
                        $f->delete();
                    }
                }
            }
        }
    }

    /**
     * Delete feed relationships
     *
     * @param  AbstractController $controller
     * @param  Application        $application
     * @return void
     */
    public static function delete(AbstractController $controller, Application $application)
    {
        if ($_POST) {
            if (isset($_POST['process_content']) && isset($_POST['content_process_action']) && ($_POST['content_process_action'] == -3)) {
                foreach ($_POST['process_content'] as $id) {
                    $feed = Table\Feed::findById([$id, 'content']);
                    if (isset($feed->id)) {
                        $feed->delete();
                    }
                }
            } else if (isset($_POST['rm_media'])) {
                foreach ($_POST['rm_media'] as $id) {
                    $feed = Table\Feed::findById([$id, 'media']);
                    if (isset($feed->id)) {
                        $feed->delete();
                    }
                }
            }
        }
    }

}
