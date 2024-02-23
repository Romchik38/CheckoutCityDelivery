<?php
declare(strict_types=1);

namespace Romchik38\CheckoutCityDelivery\Model\Config\Backend\Checkout;
          
use Magento\Framework\App\Config\Value;

class Cart extends Value
{
    public function afterSave()
    {
        if ($this->isValueChanged()) {
            $this->cacheTypeList->invalidate(\Magento\Framework\App\Cache\Type\Config::TYPE_IDENTIFIER);
        }
        $data = $this->_getData('fieldset_data');
        return parent::afterSave();
    }
}
