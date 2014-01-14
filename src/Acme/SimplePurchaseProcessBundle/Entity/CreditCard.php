<?php
namespace Acme\SimplePurchaseProcessBundle\Entity;

use Symfony\Component\Validator\ExecutionContext;

class CreditCard
{
    /**
     * @var string $number
     */
    protected $number;

    /**
     * @var integer $expiry_date_month
     */
    protected $expiry_date_month;

    /**
     * @var integer $expiry_date_year
     */
    protected $expiry_date_year;

    /**
     * @var text $csc
     */
    protected $cvv;

    /**
     * @var text $card_holder
     */
    protected $card_holder;

    /**
     * @var text $card_type
     */
    protected $card_type;

    /*protected $postcode;*/

    /**
     * @var bool $terms
     */
    protected $terms;

    /**
     * @return string $card_type
     */
    public function getCardType()
    {
        return $this->card_type;
    }

    /**
     * @param string $card_type
     */
    public function setCardType($card_type)
    {
        $this->card_type = $card_type;
    }

    /**
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param string $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

    /**
     * @return integer
     */
    public function getExpiryDateMonth()
    {
        return $this->expiry_date_month;
    }

    /**
     * @param integer $month
     */
    public function setExpiryDateMonth($month)
    {
        $this->expiry_date_month = $month;
    }

    /**
     * @return integer
     */
    public function getExpiryDateYear()
    {
        return $this->expiry_date_year;
    }

    /**
     * @param integer $year
     */
    public function setExpiryDateYear($year)
    {
        $this->expiry_date_year = $year;
    }

    /**
     * @return string
     */
    public function getCvv()
    {
        return $this->cvv;
    }

    /**
     * @param string $csc
     */
    public function setCvv($cvv)
    {
        $this->cvv = $cvv;
    }

    /**
     * @return string
     */
    public function getCardHolder()
    {
        return $this->card_holder;
    }

    /**
     * @param string $cardHolder
     */
    public function setCardHolder($cardHolder)
    {
        $this->card_holder = $cardHolder;
    }

    public function isExpireDateValid(ExecutionContext $context)
    {
        $now = new \DateTime();
        $thisYear = (int) $now->format('Y');
        $thisMonth = (int) $now->format('m');
        $expYear = (int) $this->getExpiryDateYear();
        $expMonth = (int) $this->getExpiryDateMonth();

        if ($thisYear > $expYear  || ($thisYear == $expYear && $thisMonth > $expMonth)) {
            $context->addViolationAt('expiry_date_month', 'expired card', array(), null);
        }
    }

    /**
     * @param boolean $terms
     */
    public function setTerms($terms)
    {
        $this->terms = $terms;
    }

    /**
     * @return boolean
     */
    public function getTerms()
    {
        return $this->terms;
    }
}
