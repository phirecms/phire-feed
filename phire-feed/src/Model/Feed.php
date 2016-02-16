<?php

namespace Phire\Feed\Model;

use Pop\Feed\Writer;
use Phire\Feed\Table;
use Phire\Model\AbstractModel;

class Feed extends AbstractModel
{

    /**
     * Get the feed
     *
     * @param  array               $feedHeaders
     * @param  string              $feedType
     * @param  int                 $feedLimit
     * @param  \Pop\Module\Manager $modules
     * @return Writer
     */
    public function getFeed($feedHeaders, $feedType, $feedLimit, \Pop\Module\Manager $modules)
    {
        $items = [];
        $feed  = Table\Feed::findAll();

        foreach ($feed->rows() as $f) {
            if ($f->type == 'content') {
                if ($modules->isRegistered('phire-fields')) {
                    $item = \Phire\Fields\Model\FieldValue::getModelObject('Phire\Content\Model\Content', ['id' => $f->id]);
                } else {
                    $item = new \Phire\Content\Model\Content();
                    $item->getById($f->id);
                }
                if (($item->status == 1) && (count($item->roles) == 0)) {
                    $items[] = $this->formatItem($item, 'content', $feedType);
                }
            } else if ($f->type == 'media') {
                if ($modules->isRegistered('phire-fields')) {
                    $item = \Phire\Fields\Model\FieldValue::getModelObject('Phire\Media\Model\Media', ['id' => $f->id]);
                } else {
                    $item = new \Phire\Media\Model\Media();
                    $item->getById($f->id);
                }

                $item->publish = $item->uploaded;
                $items[] = $this->formatItem($item, 'media', $feedType);
            }
        }

        usort($items, function ($a, $b)
        {
            $t1 = strtotime($a['publish']);
            $t2 = strtotime($b['publish']);
            return $t2 - $t1;
        });

        if ((int)$feedLimit > 0) {
            if (count($items) > (int)$feedLimit) {
                $items = array_slice($items, 0, (int)$feedLimit);
            }
        }

        $writer = new Writer($feedHeaders, $items);
        if ($feedType == 'atom') {
            $writer->setAtom();
        }

        return $writer;
    }

    /**
     * Format the feed item
     *
     * @param  \ArrayObject $item
     * @param  string       $type
     * @param  string       $feedType
     * @return array
     */
    public function formatItem($item, $type, $feedType)
    {
        if ($type == 'content') {
            $item->link    = 'http://' . $_SERVER['HTTP_HOST'] . BASE_PATH . $item->uri;
            $item->pubDate = $item->publish;

            if (isset($item->description)) {
                $item->summary = $item->description;
            }

            unset($item->id);
            unset($item->type_id);
            unset($item->parent_id);
            unset($item->uri);
            unset($item->slug);
            unset($item->status);
            unset($item->template);
            unset($item->order);
            unset($item->roles);
            unset($item->force_ssl);
            unset($item->hierarchy);
            unset($item->created_by);
            unset($item->updated_by);
            unset($item->content_type);
            unset($item->content_type_force_ssl);
            unset($item->strict_publishing);
            unset($item->publish_month);
            unset($item->publish_day);
            unset($item->publish_year);
            unset($item->publish_hour);
            unset($item->publish_minute);
            unset($item->created_by_username);
            unset($item->updated_by_username);
            unset($item->content_parent_id);
            unset($item->content_status);
            unset($item->content_template);
            unset($item->breadcrumb);
            unset($item->breadcrumb_text);

            if (null === $item->expire) {
                unset($item->expire);
            }
        } else if ($type == 'media') {
            $library = \Phire\Media\Table\MediaLibraries::findById($item->library_id);
            $item->link    = 'http://' . $_SERVER['HTTP_HOST'] . BASE_PATH . CONTENT_PATH . '/' . $library->folder . '/'. $item->file;
            $item->pubDate = $item->uploaded;
            $item->icon    = 'http://' . $_SERVER['HTTP_HOST'] . $item->icon;

            if (isset($item->description)) {
                $item->summary = $item->description;
                if ($feedType == 'rss') {
                    $item->description = '<![CDATA[<img src="' . $item->link . '" width="320" /><p>' . $item->description . '</p>]]>';
                }
            } else {
                if ($feedType == 'rss') {
                    $item->description = '<![CDATA[<img src="' . $item->link . '" width="320" />]]>';
                }
            }

            unset($item->id);
            unset($item->library_id);
            unset($item->library_folder);
            unset($item->order);
        }

        return $item->toArray();
    }

}
