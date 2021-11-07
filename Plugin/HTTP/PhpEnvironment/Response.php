<?php

/**
 * Created by Hidro Le.
 * Email: hieu@solutiontutorials.com
 * Date: 19/08/2021
 * Time: 22:55
 */

namespace Hidro\CoreWebVitals\Plugin\HTTP\PhpEnvironment;

class Response
{

    /**
     * @param \Magento\Framework\HTTP\PhpEnvironment\Response $subject
     * @param string                                          $name
     * @param string                                          $value
     * @param bool                                            $replace
     * @return array
     */
    public function beforeSetHeader(
        \Magento\Framework\HTTP\PhpEnvironment\Response $subject,
        $name,
        $value,
        $replace = false
    ) {
        if (is_array($value)) {
            $value = implode(', ', $value);
        }
        return [$name, $value, $replace];
    }
}
