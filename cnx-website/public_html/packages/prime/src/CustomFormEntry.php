<?php

namespace Concrete\Package\Prime\Src;

/**
 * @Entity
 * @Table(
 *     name="CustomFormEntries"
 * )
 */
class CustomFormEntry
{
    /**
     * @Id
     * @Column(name="submissionID", type="integer", options={"unsigned"=true})
     * @GeneratedValue(strategy="AUTO")
     */
    protected $submissionID;

    /**
     * @Column(type="text")
     */
    protected $boatName;

    /**
     * @Column(type="text")
     */
    protected $yourName;

    /**
     * @Column(type="text")
     */
    protected $email;

    /**
     * @Column(type="text")
     */
    protected $phone;

    /**
     * @Column(type="text")
     */
    protected $message;

    /**
     * @Column(type="datetime")
     */
    protected $dateSubmitted;

    /**
     * @Column(type="text")
     */
    protected $ipAddress;

    /**
     * @Column(type="integer",  options={"default": 0, "unsigned"=true})
     */
    protected $cID;

    /**
     * @param mixed $boatName
     * @return CustomFormEntry
     */
    public function setBoatName($boatName)
    {
        $this->boatName = $boatName;
        return $this;
    }

    /**
     * @param mixed $yourName
     * @return CustomFormEntry
     */
    public function setYourName($yourName)
    {
        $this->yourName = $yourName;
        return $this;
    }

    /**
     * @param mixed $email
     * @return CustomFormEntry
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @param mixed $phone
     * @return CustomFormEntry
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @param mixed $message
     * @return CustomFormEntry
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @param mixed $dateSubmitted
     * @return CustomFormEntry
     */
    public function setDateSubmitted($dateSubmitted)
    {
        $this->dateSubmitted = $dateSubmitted;
        return $this;
    }

    /**
     * @param mixed $ipAddress
     * @return CustomFormEntry
     */
    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;
        return $this;
    }

    /**
     * @param mixed $cID
     * @return CustomFormEntry
     */
    public function setPageID($cID)
    {
        $this->cID = $cID;
        return $this;
    }

    public function AddEntry($data)
    {
        $product = new self();
        $product->setBoatName($data['boat-name']);
        $product->setYourName($data['your-name']);
        $product->setEmail($data['email']);
        $product->setPhone($data['phone']);
        $product->setMessage($data['message']);
        $product->setIpAddress($_SERVER['REMOTE_ADDR']);
        $product->setPageID($data['cID']);
        $product->setDateSubmitted(new \DateTime());
        $em = \ORM::entityManager();
        $em->persist($product);
        $em->flush();
    }

}

?>
