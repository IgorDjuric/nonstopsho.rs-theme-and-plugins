<?php

namespace Nss\Feed;


class Product
{
    private $vendor_sku;

    private $category;

    private $name;

    /**
     * The post status
     *
     * publish|pending|draft|private|static|object|attachment|inherit|future|trash.
     */
    private $status;

    private $short_description;

    private $description;

    private $image;

    private $regular_price;

    private $sale_price;

    private $input_price;

    private $vendor_id;

    private $in_stock;

    private $pdv;
    private $size;
    private $postpaid;
    private $manufacturer;

    /**
     * @param $supplierSku
     * @param $categoryIds
     * @param $name
     * @param $status
     * @param $shortDescription
     * @param $description
     * @param $imageIds
     * @param $images
     * @param $regularPrice
     * @param $salePrice
     * @param $inputPrice
     * @param $vendorId
     * @param $stockStatus
     */
    public $dto = [
        'postId' => '',
        'supplierSku' => '',
        'supplierId' => '',
        'categoryIds' => '',
        'name' => '',
        'status' => '',
        'shortDescription' => '',
        'description' => '',
        'imageIds' => '',
        'images' => '',
        'regularPrice' => '',
        'salePrice' => '',
        'inputPrice' => '',
        'stockStatus' => '',
        'pdv' => '',
        'attributes' => '',
        'postPaid' => '',
        'manufacturer' => '',
        'synced' => '',
        'weight' => '',
        'updatedAt' => '',
        'createdAt' => '',
        'quantity' => '',
        'sku' => '',
        'boja' => '',
        'velicina' => '',
        'type' => '',
        'thumbnail' => '',
        'permalink' => '',
    ];

    /**
     * Product constructor.
     *
     */
    public function __construct(
//        $vendor_sku, $category, $name, $status, $short_description, $description, $image,
//        $regular_price, $sale_price, $input_price, $vendor_id, $pdv, $size, $postpaid, $manufacturer, $in_stock
    $dto
    ) {

        $this->dto = $dto;
//        $this->vendor_sku = $vendor_sku;
//        $this->category = $category;
//        $this->name = $name;
//        $this->status = $status;
//        $this->short_description = $short_description;
//        $this->description = $description;
//        $this->image = $image;
//        $this->regular_price = $regular_price;
//        $this->sale_price = $sale_price;
//        $this->input_price = $input_price;
//        $this->vendor_id = $vendor_id;
//        $this->pdv = $pdv;
//        $this->size = $size;
//        $this->postpaid = $postpaid;
//        $this->manufacturer = $manufacturer;
//        $this->in_stock = $in_stock;

    }

    public function getBoja()
    {
        return $this->dto['boja'];
    }

    public function getVelicina()
    {
        return $this->dto['velicina'];
    }

    public function getSku()
    {
        return $this->dto['sku'];
    }

    public function getCreatedAt()
    {
        return $this->dto['createdAt'];
    }

    public function getCategoryIds()
    {
        return $this->dto['categoryIds'];
    }

    public function getWeight()
    {
        return $this->dto['weight'];
    }

    public function getQuantity()
    {
        return $this->dto['quantity'];
    }

    public function getStockStatus()
    {
        return $this->dto['stockStatus'];
    }

    /**
     * @return mixed
     */
    public function getSupplierId()
    {
        return $this->dto['supplierId'];
    }

    /**
     * @return mixed
     */
    public function getSupplierSku()
    {
        return $this->dto['supplierSku'];
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->dto['name'];
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->dto['status'];
    }

    /**
     * @return mixed
     */
    public function getShortDescription()
    {
        return $this->dto['shortDescription'];
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->dto['description'];
    }

    /**
     * @return mixed
     */
    public function getImageAt($index = 0)
    {
        return explode(',', $this->dto['images'])[$index];
    }

    public function getImages()
    {
        return $this->dto['images'];
    }

    /**
     * @return mixed
     */
    public function getRegularPrice()
    {
        return $this->dto['regularPrice'];
    }

    /**
     * @return mixed
     */
    public function getSalePrice()
    {
        return $this->dto['salePrice'];
    }

    /**
     * @return mixed
     */
    public function getInputPrice()
    {
        return $this->dto['inputPrice'];
    }

    public function getPdv(){
        return $this->dto['pdv'];
    }

    public function getManufacturer(){
        return $this->dto['manufacturer'];
    }

    public function getType() {
        return $this->dto['type'];
    }
}
