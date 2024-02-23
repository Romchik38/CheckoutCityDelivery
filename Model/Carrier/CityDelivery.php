<?php

declare(strict_types=1);

namespace Romchik38\CheckoutCityDelivery\Model\Carrier;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Quote\Model\Quote\Address\RateResult\Method;
use Magento\Quote\Model\Quote\Address\RateResult\MethodFactory;
use Magento\Quote\Model\Quote\Address\RateResult\Error;
use Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory;
use Magento\Shipping\Model\Carrier\AbstractCarrier;
use Magento\Shipping\Model\Carrier\CarrierInterface;
use Magento\Shipping\Model\Rate\Result;
use Magento\Shipping\Model\Rate\ResultFactory;
use Psr\Log\LoggerInterface;

class CityDelivery extends AbstractCarrier implements CarrierInterface
{
    protected $_code = 'citydelivery';

    protected $_isFixed = true;

    private ResultFactory $rateResultFactory;

    private MethodFactory $rateMethodFactory;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        ErrorFactory $rateErrorFactory,
        LoggerInterface $logger,
        ResultFactory $rateResultFactory,
        MethodFactory $rateMethodFactory,
        array $data = []
    ) {
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);

        $this->rateResultFactory = $rateResultFactory;
        $this->rateMethodFactory = $rateMethodFactory;
        $this->rateErrorFactory = $rateErrorFactory;
    }

    /**
     * Custom Shipping Rates Collector
     *
     * @param RateRequest $request
     * @return \Magento\Shipping\Model\Rate\Result|bool
     */
    public function collectRates(RateRequest $request)
    {
     
        if (!$this->getConfigFlag('active')) {
            return false;
        }

        $data = $request->getData();
        $city = $data['dest_city'];
        // Working with Errors
        $error = $this->checkError($city);
        if ($error) {
            if ($this->getConfigData('showmethod')) {
                return $error;
            } else {
                return false;
            }
        }

        //No Errors. Working with data
        /** @var Method $method */
        $method = $this->rateMethodFactory->create();

        $method->setCarrier($this->_code);
        $method->setCarrierTitle($this->getConfigData('title'));

        $method->setMethod($this->_code);
        $method->setMethodTitle($this->getConfigData('name'));

        $shippingCost = (float) $this->getConfigData('shipping_cost');
        $method->setPrice($shippingCost);
        $method->setCost($shippingCost);
        $method->setBaseSubtotalWithDiscountInclTax($data['base_subtotal_with_discount_incl_tax']);
        //free shipping
        if ($this->getConfigData('freeshipping_enable')) {
            $this->freeShipping($method);
        }

        /** @var Result $result */
        $result = $this->rateResultFactory->create();
        $result->append($method);

        return $result;
    }

    public function getAllowedMethods(): array
    {
        return [$this->_code => $this->getConfigData('name')];
    }

    private function checkError(
        null | string $city, 
        string $encoding = 'utf-8'
        ): bool | Error
    {

        $error = $this->rateErrorFactory->create();
        $error->setCarrier($this->_code);
        $error->setCarrierTitle($this->getConfigData('title'));
        $errorMsg = $this->getConfigData('error_message');
        $error->setErrorMessage(
            $errorMsg ? $errorMsg : __(
                'Sorry, but we can\'t deliver to the destination city with this shipping module.'
            )
        );

        //check city list
        $cityList = $this->getConfigData('city_list');
        if (!$cityList) {
            $error->setErrorMessage(__('City list does\'nt configured'));
            return $error;
        }

        //check city
        if(!$city){
            $error->setErrorMessage(__('City field is required for city dilivery'));
            return $error;
        }
        //match city
        $cityArr = explode(':', $cityList);
        $cityLower = mb_convert_case($city, MB_CASE_LOWER, $encoding);
        foreach($cityArr as $val) {
            $valLower = mb_convert_case($val, MB_CASE_LOWER, $encoding);
            if ($cityLower === $valLower) {
                return false;
            }
        }
        $error->setErrorMessage(__('City dilivery is available only in Kyiv'));
        return $error;
    }

    public function freeShipping(Method $method)
    {
        $t = $this->getConfigData('freeshipping_threshold');
        $threshold = (int) $t;
        if (!is_int($threshold)) {
            return;
        }
        $total = $method->getBaseSubtotalWithDiscountInclTax();
        if ($total > $threshold) {
            $method->setCost('0.00');
            $method->setPrice('0.00');
            $method->setCarrierTitle(__('City Dilivery for Free'));
        } else {
            $delta = $threshold - $total;
            $method->setCarrierTitle(__('Spend ' . $delta .' more to get FREE SHIPPING'));
        }
    }
}
