<?php

namespace TheHostingTool\RSS;

use DOMDocument;

class RSSParser
{
    protected $feed_url;
    protected $rssParser;
    protected $feed = array();

    public function __construct($url) {

       $this->feed_url = $url;
       $this->rssParser = new DOMDocument();

       $this->rssParser->load($this->feed_url);

       foreach ($this->rssParser->getElementsByTagName('item') as $node) {
           $item = array (
               'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
               'desc' => $node->getElementsByTagName('description')->item(0)->nodeValue,
               'link' => $node->getElementsByTagName('link')->item(0)->nodeValue,
               'date' => $node->getElementsByTagName('pubDate')->item(0)->nodeValue,
           );
           array_push($this->feed, $item);
       }
    }

    public function getFeed() {

        return $this->feed;
    }
}
