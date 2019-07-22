<?php
/**
 * MB "Vienas bitas" (Magetrend.com)
 *
 * @category MageTrend
 * @package  Magetend/PdfTemplates
 * @author   Edvinas Stulpinas <edwin@magetrend.com>
 * @license  http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link     https://www.magetrend.com/magento-2-pdf-invoice-pro
 */

namespace Magetrend\PdfTemplates\Controller\Adminhtml\PrintPdf;

use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * Print Order controller class
 *
 * @category MageTrend
 * @package  Magetend/PdfTemplates
 * @author   Edvinas Stulpinas <edwin@magetrend.com>
 * @license  http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link     https://www.magetrend.com/magento-2-pdf-invoice-pro
 */
class Order extends \Magento\Backend\App\Action
{
    public $template;

    public $orderRepository;

    public $moduleHelper;

    public $fileFactory;

    public $filesystem;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magetrend\PdfTemplates\Model\Template $template,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        \Magetrend\PdfTemplates\Helper\Data $moduleHelper,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Framework\Filesystem $filesystem
    ) {
        $this->moduleHelper = $moduleHelper;
        $this->template = $template;
        $this->orderRepository = $orderRepository;
        $this->fileFactory = $fileFactory;
        $this->filesystem = $filesystem;
        parent::__construct($context);
    }

    public function execute()
    {
        $orderId = (int)$this->getRequest()->getParam('order_id');
        $order = $this->orderRepository->get($orderId);
        $storeId = $order->getStoreId();

        if (!$this->moduleHelper->isActive($storeId) || !$this->moduleHelper->isEnabledOnFrontend($storeId)) {
            return;
        }

        $fileName = '';
        $pdf = false;

        if (isset($order)) {
            $pdf = $this->template->getPdf([$order]);
            $fileName = sprintf('order_%s.pdf', $this->moduleHelper->prepareFileName($order->getIncrementId()));
        }


        if ($pdf) {
            $path = $this->filesystem->getDirectoryRead(DirectoryList::TMP)->getAbsolutePath($fileName);
            $pdf->save($path);
            return $this->fileFactory->create(
                $fileName,
                [
                    'value'=> $fileName,
                    'type' => 'filename',
                    'rm' => true
                ],
                DirectoryList::TMP
            );
        }
    }

    /**
     * Check if user has enough privileges
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magento_Sales::sales_order');
    }
}
