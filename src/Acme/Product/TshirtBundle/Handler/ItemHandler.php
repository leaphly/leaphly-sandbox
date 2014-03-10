<?php
namespace Acme\Product\TshirtBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\Form\FormFactoryInterface;
use Leaphly\Cart\Provider\ProductFamilyProviderInterface;
use Leaphly\Cart\Model\CartInterface;
use Leaphly\Cart\Model\ItemInterface;
use Leaphly\Cart\Handler\ItemHandlerInterface;
use Leaphly\Cart\Handler\ItemHandler as BaseItemHandler;

class ItemHandler extends BaseItemHandler implements ItemHandlerInterface
{
    protected $formFactory;
    protected $objectManager;
    protected $itemClass;
    protected $itemFormTypeClass;
    protected $productClass;

    public function __construct(
        ObjectManager $objectManager,
        FormFactoryInterface $formFactory,
        $itemClass,
        $itemFormTypeClass,
        $productClass
    )
    {
        $this->objectManager = $objectManager;
        $this->itemClass = $itemClass;
        $this->formFactory = $formFactory;
        $this->itemFormTypeClass = $itemFormTypeClass;
        $this->productClass = $productClass;
    }

    /**
     * {@inheritdoc}
     */
    public function postItem(CartInterface $cart, array $parameters)
    {
        $item = $this->processForm('post', $parameters);
        //  Put here your Domain logic
        $product = $this->findProduct($parameters);
        $item->setPrice($product->getPrice());
        // adding a 20% off, better use a conference discount service :).
        $item->setFinalPrice(bcsub($product->getPrice(), $product->getPrice()*20/100));

        return $item;
    }

    /**
     * {@inheritdoc}
     */
    public function patchItem(CartInterface $cart, ItemInterface $item, array $parameters)
    {
        $item = $this->processForm('patch', $parameters, $item);
        //  Put here your Domain logic
        $product = $this->findProduct($parameters);
        $item->setPrice($product->getPrice());
        // adding a 20% off, better use a conference discount service :).
        $item->setFinalPrice(bcsub($product->getPrice(), $product->getPrice()*20/100));

        return $item;
    }

    /**
     * {@inheritdoc}
     */
    protected function createForm($method, array $parameters, ItemInterface $item = null)
    {
        if (!$item) {
            $item = new $this->itemClass;
        }

        return $this->formFactory->create(new $this->itemFormTypeClass, $item);
    }

    /**
     * It assumes that your are using the product_id parameter method.
     *
     * @param  array      $parameters
     * @return object
     * @throws \Exception
     */
    protected function findProduct(array $parameters)
    {
        $productRepository = $this->objectManager->getRepository($this->productClass);

        if (!isset($parameters[ProductFamilyProviderInterface::PRODUCT_ID_PARAMETER])) {
            throw new \Exception("Product not found");
        }

        return $productRepository->find($parameters[ProductFamilyProviderInterface::PRODUCT_ID_PARAMETER]);
    }
}
