<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Agreements")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AgreementRepository")
 */
class Agreement implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $contisAgreementCode;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $currencyCode;

    /**
     * @ORM\Column(type="long", nullable=false)
     */
    protected $newCardCharge;

    /**
     * @ORM\Column(type="long", nullable=false)
     */
    protected $cardReissueCharge;

    /**
     * @ORM\Column(type="long", nullable=false)
     */
    protected $localATMwithdrawCharge;

    /**
     * @ORM\Column(type="long", nullable=false)
     */
    protected $abroadATMwithdrawCharge;

    /**
     * @ORM\Column(type="long", nullable=false)
     */
    protected $maxBalance;

    /**
     * @ORM\Column(type="long", nullable=false)
     */
    protected $cardLimit;

    /**
     * @ORM\Column(type="long", nullable=false)
     */
    protected $monthlyAccountFee;

    /**
     * @ORM\Column(type="long", nullable=false)
     */
    protected $dailySpendLimit;

    /**
     * @ORM\Column(type="long", nullable=false)
     */
    protected $monthlySpendLimit;

    /**
     * @ORM\Column(type="long", nullable=false)
     */
    protected $maxNoOfAdditionalCards;

    /**
     * @ORM\Column(type="long", nullable=false)
     */
    protected $ATMWeeklySpendLimit;

    /**
     * @ORM\Column(type="long", nullable=false)
     */
    protected $ATMMonthlySpendLimit;

    /**
     * @ORM\Column(type="long", nullable=false)
     */
    protected $cashBackDailyLimit;

    /**
     * @ORM\Column(type="long", nullable=false)
     */
    protected $cashBackWeeklyLimit;

    /**
     * @ORM\Column(type="long", nullable=false)
     */
    protected $cashBackMonthlyLimit;

    /**
     * @ORM\Column(type="long", nullable=false)
     */
    protected $cashBackYearlyLimit;

    /**
     * @ORM\OneToMany(targetEntity="Account", mappedBy="agreement")
     */
    protected $accounts;

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'newCardCharge' => $this->newCardCharge
        ];
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->accounts = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set contisAgreementCode
     *
     * @param string $contisAgreementCode
     *
     * @return Agreement
     */
    public function setContisAgreementCode($contisAgreementCode)
    {
        $this->contisAgreementCode = $contisAgreementCode;

        return $this;
    }

    /**
     * Get contisAgreementCode
     *
     * @return string
     */
    public function getContisAgreementCode()
    {
        return $this->contisAgreementCode;
    }

    /**
     * Add account
     *
     * @param Account $account
     *
     * @return Agreement
     */
    public function addAccount(Account $account)
    {
        $this->accounts[] = $account;

        return $this;
    }

    /**
     * Remove account
     *
     * @param Account $account
     */
    public function removeAccount(Account $account)
    {
        $this->accounts->removeElement($account);
    }

    /**
     * Get accounts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAccounts()
    {
        return $this->accounts;
    }

    /**
     * Set currencyCode
     *
     * @param string $currencyCode
     *
     * @return Agreement
     */
    public function setCurrencyCode($currencyCode)
    {
        $this->currencyCode = $currencyCode;

        return $this;
    }

    /**
     * Get currencyCode
     *
     * @return string
     */
    public function getCurrencyCode()
    {
        return $this->currencyCode;
    }

    /**
     * Set newCardCharge
     *
     * @param long $newCardCharge
     *
     * @return Agreement
     */
    public function setNewCardCharge($newCardCharge)
    {
        $this->newCardCharge = $newCardCharge;

        return $this;
    }

    /**
     * Get newCardCharge
     *
     * @return long
     */
    public function getNewCardCharge()
    {
        return $this->newCardCharge;
    }

    /**
     * Set cardReissueCharge
     *
     * @param long $cardReissueCharge
     *
     * @return Agreement
     */
    public function setCardReissueCharge($cardReissueCharge)
    {
        $this->cardReissueCharge = $cardReissueCharge;

        return $this;
    }

    /**
     * Get cardReissueCharge
     *
     * @return long
     */
    public function getCardReissueCharge()
    {
        return $this->cardReissueCharge;
    }

    /**
     * Set localATMwithdrawCharge
     *
     * @param long $localATMwithdrawCharge
     *
     * @return Agreement
     */
    public function setLocalATMwithdrawCharge($localATMwithdrawCharge)
    {
        $this->localATMwithdrawCharge = $localATMwithdrawCharge;

        return $this;
    }

    /**
     * Get localATMwithdrawCharge
     *
     * @return long
     */
    public function getLocalATMwithdrawCharge()
    {
        return $this->localATMwithdrawCharge;
    }

    /**
     * Set abroadATMwithdrawCharge
     *
     * @param long $abroadATMwithdrawCharge
     *
     * @return Agreement
     */
    public function setAbroadATMwithdrawCharge($abroadATMwithdrawCharge)
    {
        $this->abroadATMwithdrawCharge = $abroadATMwithdrawCharge;

        return $this;
    }

    /**
     * Get abroadATMwithdrawCharge
     *
     * @return long
     */
    public function getAbroadATMwithdrawCharge()
    {
        return $this->abroadATMwithdrawCharge;
    }

    /**
     * Set maxBalance
     *
     * @param long $maxBalance
     *
     * @return Agreement
     */
    public function setMaxBalance($maxBalance)
    {
        $this->maxBalance = $maxBalance;

        return $this;
    }

    /**
     * Get maxBalance
     *
     * @return long
     */
    public function getMaxBalance()
    {
        return $this->maxBalance;
    }

    /**
     * Set cardLimit
     *
     * @param long $cardLimit
     *
     * @return Agreement
     */
    public function setCardLimit($cardLimit)
    {
        $this->cardLimit = $cardLimit;

        return $this;
    }

    /**
     * Get cardLimit
     *
     * @return long
     */
    public function getCardLimit()
    {
        return $this->cardLimit;
    }

    /**
     * Set monthlyAccountFee
     *
     * @param long $monthlyAccountFee
     *
     * @return Agreement
     */
    public function setMonthlyAccountFee($monthlyAccountFee)
    {
        $this->monthlyAccountFee = $monthlyAccountFee;

        return $this;
    }

    /**
     * Get monthlyAccountFee
     *
     * @return long
     */
    public function getMonthlyAccountFee()
    {
        return $this->monthlyAccountFee;
    }

    /**
     * Set dailySpendLimit
     *
     * @param long $dailySpendLimit
     *
     * @return Agreement
     */
    public function setDailySpendLimit($dailySpendLimit)
    {
        $this->dailySpendLimit = $dailySpendLimit;

        return $this;
    }

    /**
     * Get dailySpendLimit
     *
     * @return long
     */
    public function getDailySpendLimit()
    {
        return $this->dailySpendLimit;
    }

    /**
     * Set monthlySpendLimit
     *
     * @param long $monthlySpendLimit
     *
     * @return Agreement
     */
    public function setMonthlySpendLimit($monthlySpendLimit)
    {
        $this->monthlySpendLimit = $monthlySpendLimit;

        return $this;
    }

    /**
     * Get monthlySpendLimit
     *
     * @return long
     */
    public function getMonthlySpendLimit()
    {
        return $this->monthlySpendLimit;
    }

    /**
     * Set maxNoOfAdditionalCards
     *
     * @param long $maxNoOfAdditionalCards
     *
     * @return Agreement
     */
    public function setMaxNoOfAdditionalCards($maxNoOfAdditionalCards)
    {
        $this->maxNoOfAdditionalCards = $maxNoOfAdditionalCards;

        return $this;
    }

    /**
     * Get maxNoOfAdditionalCards
     *
     * @return long
     */
    public function getMaxNoOfAdditionalCards()
    {
        return $this->maxNoOfAdditionalCards;
    }

    /**
     * Set aTMWeeklySpendLimit
     *
     * @param long $aTMWeeklySpendLimit
     *
     * @return Agreement
     */
    public function setATMWeeklySpendLimit($aTMWeeklySpendLimit)
    {
        $this->ATMWeeklySpendLimit = $aTMWeeklySpendLimit;

        return $this;
    }

    /**
     * Get aTMWeeklySpendLimit
     *
     * @return long
     */
    public function getATMWeeklySpendLimit()
    {
        return $this->ATMWeeklySpendLimit;
    }

    /**
     * Set aTMMonthlySpendLimit
     *
     * @param long $aTMMonthlySpendLimit
     *
     * @return Agreement
     */
    public function setATMMonthlySpendLimit($aTMMonthlySpendLimit)
    {
        $this->ATMMonthlySpendLimit = $aTMMonthlySpendLimit;

        return $this;
    }

    /**
     * Get aTMMonthlySpendLimit
     *
     * @return long
     */
    public function getATMMonthlySpendLimit()
    {
        return $this->ATMMonthlySpendLimit;
    }

    /**
     * Set cashBackDailyLimit
     *
     * @param long $cashBackDailyLimit
     *
     * @return Agreement
     */
    public function setCashBackDailyLimit($cashBackDailyLimit)
    {
        $this->cashBackDailyLimit = $cashBackDailyLimit;

        return $this;
    }

    /**
     * Get cashBackDailyLimit
     *
     * @return long
     */
    public function getCashBackDailyLimit()
    {
        return $this->cashBackDailyLimit;
    }

    /**
     * Set cashBackWeeklyLimit
     *
     * @param long $cashBackWeeklyLimit
     *
     * @return Agreement
     */
    public function setCashBackWeeklyLimit($cashBackWeeklyLimit)
    {
        $this->cashBackWeeklyLimit = $cashBackWeeklyLimit;

        return $this;
    }

    /**
     * Get cashBackWeeklyLimit
     *
     * @return long
     */
    public function getCashBackWeeklyLimit()
    {
        return $this->cashBackWeeklyLimit;
    }

    /**
     * Set cashBackMonthlyLimit
     *
     * @param long $cashBackMonthlyLimit
     *
     * @return Agreement
     */
    public function setCashBackMonthlyLimit($cashBackMonthlyLimit)
    {
        $this->cashBackMonthlyLimit = $cashBackMonthlyLimit;

        return $this;
    }

    /**
     * Get cashBackMonthlyLimit
     *
     * @return long
     */
    public function getCashBackMonthlyLimit()
    {
        return $this->cashBackMonthlyLimit;
    }

    /**
     * Set cashBackYearlyLimit
     *
     * @param long $cashBackYearlyLimit
     *
     * @return Agreement
     */
    public function setCashBackYearlyLimit($cashBackYearlyLimit)
    {
        $this->cashBackYearlyLimit = $cashBackYearlyLimit;

        return $this;
    }

    /**
     * Get cashBackYearlyLimit
     *
     * @return long
     */
    public function getCashBackYearlyLimit()
    {
        return $this->cashBackYearlyLimit;
    }
}
