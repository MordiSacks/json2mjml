<?php

namespace Json2Mjml;


class Json2Mjml
{
    public $tagConversion = [
        'mj-dev' => 'mj-raw',
    ];

    protected function indentPad($n)
    {
        return str_repeat(' ', $n + 1);
    }


    /**
     * Convert attributes array to xml style attributes
     *
     * @param array $attributes
     *
     * @return string
     */
    protected function lineAttributes($attributes)
    {
        $attributes = array_filter($attributes, function ($key) {
            return $key !== 'passport';
        }, ARRAY_FILTER_USE_KEY);

        $res = '';

        foreach ($attributes as $key => $value) {
            $res .= $key . '="' . $value . '" ';
        }

        return trim($res);
    }

    public function json2xml($node, $indent = 0)
    {

        if (isset($node['mjml'])) {
            $node = $node['mjml'];
        }

        $tagName    = isset($node['tagName']) ? $node['tagName'] : null;
        $children   = isset($node['children']) ? $node['children'] : null;
        $content    = isset($node['content']) ? $node['content'] : null;
        $attributes = isset($node['attributes']) ? $node['attributes'] : null;

        if (array_key_exists($tagName, $this->tagConversion)) {
            $tagName = $this->tagConversion[$tagName];
        }

        $space = $this->indentPad($indent);

        $attributes = $attributes ? $this->lineAttributes($attributes) : '';

        $inside = '';

        if ($content) {
            $inside = PHP_EOL . $space . '  ' . $content . PHP_EOL . $space;
        } elseif ($children) {
            $inside = PHP_EOL . join(PHP_EOL, array_map(function ($child) use ($indent) {
                    return $this->json2xml($child, $indent + 2);
                }, $children)) . PHP_EOL . $space;
        }

        return $space . '<' . $tagName . ' ' . $attributes . '>' . $inside . '</' . $tagName . '>';
    }
}