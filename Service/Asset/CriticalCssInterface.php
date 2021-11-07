<?php
/**
 * Created by Hidro Le.
 * Email: hieu@solutiontutorials.com
 * Date: 19/08/2021
 * Time: 21:38
 */

namespace Hidro\CoreWebVitals\Service\Asset;

interface CriticalCssInterface
{
    const DEFAULT_CRITICAL_CSS_FILE = 'Hidro_CoreWebVitals::css/default.css';
    const CORE_VITAL_CSS_FILE = 'Hidro_CoreWebVitals::css/core_vital.css';
    const FONT_CRITICAL_CSS_FILE = 'Hidro_CoreWebVitals::css/fonts.css';

    /**
     * @return mixed
     */
    public function getDefaultCritical();

    /**
     * @return mixed
     */
    public function getFontCritical();

    /**
     * @param $bodyClass
     * @return string
     */
    public function getCriticalContent($bodyClass);
}
