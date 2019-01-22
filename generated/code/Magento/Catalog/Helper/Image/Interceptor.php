<?php
namespace Magento\Catalog\Helper\Image;

/**
 * Interceptor class for @see \Magento\Catalog\Helper\Image
 */
class Interceptor extends \Magento\Catalog\Helper\Image implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Helper\Context $context, \Magento\Catalog\Model\Product\ImageFactory $productImageFactory, \Magento\Framework\View\Asset\Repository $assetRepo, \Magento\Framework\View\ConfigInterface $viewConfig, \Magento\Catalog\Model\View\Asset\PlaceholderFactory $placeholderFactory = null)
    {
        $this->___init();
        parent::__construct($context, $productImageFactory, $assetRepo, $viewConfig, $placeholderFactory);
    }

    /**
     * {@inheritdoc}
     */
    public function init($product, $imageId, $attributes = array())
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'init');
        if (!$pluginInfo) {
            return parent::init($product, $imageId, $attributes);
        } else {
            return $this->___callPlugins('init', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function resize($width, $height = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'resize');
        if (!$pluginInfo) {
            return parent::resize($width, $height);
        } else {
            return $this->___callPlugins('resize', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setQuality($quality)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setQuality');
        if (!$pluginInfo) {
            return parent::setQuality($quality);
        } else {
            return $this->___callPlugins('setQuality', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function keepAspectRatio($flag)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'keepAspectRatio');
        if (!$pluginInfo) {
            return parent::keepAspectRatio($flag);
        } else {
            return $this->___callPlugins('keepAspectRatio', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function keepFrame($flag)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'keepFrame');
        if (!$pluginInfo) {
            return parent::keepFrame($flag);
        } else {
            return $this->___callPlugins('keepFrame', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function keepTransparency($flag)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'keepTransparency');
        if (!$pluginInfo) {
            return parent::keepTransparency($flag);
        } else {
            return $this->___callPlugins('keepTransparency', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function constrainOnly($flag)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'constrainOnly');
        if (!$pluginInfo) {
            return parent::constrainOnly($flag);
        } else {
            return $this->___callPlugins('constrainOnly', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function backgroundColor($colorRGB)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'backgroundColor');
        if (!$pluginInfo) {
            return parent::backgroundColor($colorRGB);
        } else {
            return $this->___callPlugins('backgroundColor', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rotate($angle)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'rotate');
        if (!$pluginInfo) {
            return parent::rotate($angle);
        } else {
            return $this->___callPlugins('rotate', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function watermark($fileName, $position, $size = null, $imageOpacity = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'watermark');
        if (!$pluginInfo) {
            return parent::watermark($fileName, $position, $size, $imageOpacity);
        } else {
            return $this->___callPlugins('watermark', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function placeholder($fileName)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'placeholder');
        if (!$pluginInfo) {
            return parent::placeholder($fileName);
        } else {
            return $this->___callPlugins('placeholder', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getPlaceholder($placeholder = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getPlaceholder');
        if (!$pluginInfo) {
            return parent::getPlaceholder($placeholder);
        } else {
            return $this->___callPlugins('getPlaceholder', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getUrl()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getUrl');
        if (!$pluginInfo) {
            return parent::getUrl();
        } else {
            return $this->___callPlugins('getUrl', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function save()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'save');
        if (!$pluginInfo) {
            return parent::save();
        } else {
            return $this->___callPlugins('save', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getResizedImageInfo()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getResizedImageInfo');
        if (!$pluginInfo) {
            return parent::getResizedImageInfo();
        } else {
            return $this->___callPlugins('getResizedImageInfo', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultPlaceholderUrl($placeholder = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getDefaultPlaceholderUrl');
        if (!$pluginInfo) {
            return parent::getDefaultPlaceholderUrl($placeholder);
        } else {
            return $this->___callPlugins('getDefaultPlaceholderUrl', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setWatermarkSize($size)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setWatermarkSize');
        if (!$pluginInfo) {
            return parent::setWatermarkSize($size);
        } else {
            return $this->___callPlugins('setWatermarkSize', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setWatermarkImageOpacity($imageOpacity)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setWatermarkImageOpacity');
        if (!$pluginInfo) {
            return parent::setWatermarkImageOpacity($imageOpacity);
        } else {
            return $this->___callPlugins('setWatermarkImageOpacity', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setImageFile($file)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setImageFile');
        if (!$pluginInfo) {
            return parent::setImageFile($file);
        } else {
            return $this->___callPlugins('setImageFile', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getOriginalWidth()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getOriginalWidth');
        if (!$pluginInfo) {
            return parent::getOriginalWidth();
        } else {
            return $this->___callPlugins('getOriginalWidth', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getOriginalHeight()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getOriginalHeight');
        if (!$pluginInfo) {
            return parent::getOriginalHeight();
        } else {
            return $this->___callPlugins('getOriginalHeight', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getOriginalSizeArray()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getOriginalSizeArray');
        if (!$pluginInfo) {
            return parent::getOriginalSizeArray();
        } else {
            return $this->___callPlugins('getOriginalSizeArray', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getType');
        if (!$pluginInfo) {
            return parent::getType();
        } else {
            return $this->___callPlugins('getType', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getWidth()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getWidth');
        if (!$pluginInfo) {
            return parent::getWidth();
        } else {
            return $this->___callPlugins('getWidth', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getHeight()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getHeight');
        if (!$pluginInfo) {
            return parent::getHeight();
        } else {
            return $this->___callPlugins('getHeight', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getFrame()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getFrame');
        if (!$pluginInfo) {
            return parent::getFrame();
        } else {
            return $this->___callPlugins('getFrame', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getLabel()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getLabel');
        if (!$pluginInfo) {
            return parent::getLabel();
        } else {
            return $this->___callPlugins('getLabel', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isModuleOutputEnabled($moduleName = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isModuleOutputEnabled');
        if (!$pluginInfo) {
            return parent::isModuleOutputEnabled($moduleName);
        } else {
            return $this->___callPlugins('isModuleOutputEnabled', func_get_args(), $pluginInfo);
        }
    }
}
