<?php
/**
 * Created by Hidro Le.
 * Email: hieu@solutiontutorials.com
 * Date: 03/10/2021
 * Time: 21:41
 */

namespace Hidro\CoreWebVitals\Model\Asset\Adapter;

class HTML implements \Magento\Framework\Code\Minifier\AdapterInterface
{

    /**
     * All inline HTML tags
     *
     * @var array
     */
    protected $inlineHtmlTags = [
        'b',
        'big',
        'i',
        'small',
        'tt',
        'abbr',
        'acronym',
        'cite',
        'code',
        'dfn',
        'em',
        'kbd',
        'strong',
        'samp',
        'var',
        'a',
        'bdo',
        'br',
        'img',
        'map',
        'object',
        'q',
        'span',
        'sub',
        'sup',
        'button',
        'input',
        'label',
        'select',
        'textarea',
        '\?',
    ];

    public function minify($content)
    {

        $_html = preg_replace(
            '#(?<!]]>)\s+</#',
            '</',
            preg_replace(
                '#((?:<\?php\s+(?!echo|print|if|elseif|else)[^\?]*)\?>)\s+#',
                '$1 ',
                preg_replace(
                    '#(?<!' . implode('|', $this->inlineHtmlTags) . ')\> \<#',
                    '><',
                    preg_replace(
                        '#(?ix)(?>[^\S ]\s*|\s{2,})(?=(?:(?:[^<]++|<(?!/?(?:textarea|pre|script)\b))*+)'
                        . '(?:<(?>textarea|pre|script)\b|\z))#',
                        ' ',
                        preg_replace(
                            '#(?<!:|\\\\|\'|")//(?!\s*\<\!\[)(?!\s*]]\>)[^\n\r]*#',
                            '',
                            preg_replace(
                                '#(?<!:|\'|")//[^\n\r]*(\?\>)#',
                                ' $1',
                                preg_replace(
                                    '#(?<!:)//[^\n\r]*(\<\?php)[^\n\r]*(\s\?\>)[^\n\r]*#',
                                    '',
                                    $content
                                )
                            )
                        )
                    )
                )
            )
        );
        return $_html;
    }
}
