<?php
/**
 * Mavenbird Technologies Private Limited
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://mavenbird.com/Mavenbird-Module-License.txt
 *
 * =================================================================
 *
 * @category   Mavenbird
 * @package    Mavenbird_OneStepCheckout
 * @author     Mavenbird Team
 * @copyright  Copyright (c) 2018-2024 Mavenbird Technologies Private Limited ( http://mavenbird.com )
 * @license    http://mavenbird.com/Mavenbird-Module-License.txt
 */

namespace Mavenbird\OneStepCheckout\Plugin\View\Page\Config;

use Magento\Framework\App\CacheInterface;
use Magento\Framework\View\Page\Config;
use Magento\Framework\View\Page\Config\Renderer as MagentoRenderer;
use Magento\Framework\View\Asset\Repository;
use Magento\Framework\View\Asset\GroupedCollection;
use Mavenbird\OneStepCheckout\Helper\Data;

class Renderer
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @var Repository
     */
    private $assetRepo;

    /**
     * @var GroupedCollection
     */
    private $pageAssets;

    /**
     * @var Data
     */
    private $helper;

    /**
     * Construct
     *
     * @param Config $config
     * @param CacheInterface $cache
     * @param Repository $assetRepo
     * @param GroupedCollection $pageAssets
     * @param Data $helper
     */
    public function __construct(
        Config $config,
        CacheInterface $cache,
        Repository $assetRepo,
        GroupedCollection $pageAssets,
        Data $helper
    ) {
        $this->config = $config;
        $this->cache = $cache;
        $this->assetRepo = $assetRepo;
        $this->pageAssets = $pageAssets;
        $this->helper = $helper;
    }

    /**
     * BeforeRenderAssets
     *
     * @param MagentoRenderer $subject
     * @param array $resultGroups
     * @return void
     */
    public function beforeRenderAssets(MagentoRenderer $subject, $resultGroups = [])
    {
        if ($this->helper->isVersionAbove('2.3.2')) {
            $file = 'Mavenbird_OneStepCheckout::js/oscPatch.js';
        } else {
            $file = 'Mavenbird_OneStepCheckout::js/oscNoPatch.js';
        }
        $asset = $this->assetRepo->createAsset($file);
        $this->pageAssets->insert($file, $asset, 'requirejs/require.js');
        return [$resultGroups];
    }
}
