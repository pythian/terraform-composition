<?php

namespace DoctrineProxies\__CG__\Concrete\Core\Entity\Site;


/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class SiteTree extends \Concrete\Core\Entity\Site\SiteTree implements \Doctrine\ORM\Proxy\Proxy
{
    /**
     * @var \Closure the callback responsible for loading properties in the proxy object. This callback is called with
     *      three parameters, being respectively the proxy object to be initialized, the method that triggered the
     *      initialization process and an array of ordered parameters that were passed to that method.
     *
     * @see \Doctrine\Common\Proxy\Proxy::__setInitializer
     */
    public $__initializer__;

    /**
     * @var \Closure the callback responsible of loading properties that need to be copied in the cloned object
     *
     * @see \Doctrine\Common\Proxy\Proxy::__setCloner
     */
    public $__cloner__;

    /**
     * @var boolean flag indicating if this object was already initialized
     *
     * @see \Doctrine\Persistence\Proxy::__isInitialized
     */
    public $__isInitialized__ = false;

    /**
     * @var array<string, null> properties to be lazy loaded, indexed by property name
     */
    public static $lazyPropertiesNames = array (
);

    /**
     * @var array<string, mixed> default values of properties to be lazy loaded, with keys being the property names
     *
     * @see \Doctrine\Common\Proxy\Proxy::__getLazyProperties
     */
    public static $lazyPropertiesDefaults = array (
);



    public function __construct(?\Closure $initializer = null, ?\Closure $cloner = null)
    {

        $this->__initializer__ = $initializer;
        $this->__cloner__      = $cloner;
    }







    /**
     *
     * @return array
     */
    public function __sleep()
    {
        if ($this->__isInitialized__) {
            return ['__isInitialized__', 'locale', 'siteTreeID', 'siteHomePageID'];
        }

        return ['__isInitialized__', 'locale', 'siteTreeID', 'siteHomePageID'];
    }

    /**
     *
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (SiteTree $proxy) {
                $proxy->__setInitializer(null);
                $proxy->__setCloner(null);

                $existingProperties = get_object_vars($proxy);

                foreach ($proxy::$lazyPropertiesDefaults as $property => $defaultValue) {
                    if ( ! array_key_exists($property, $existingProperties)) {
                        $proxy->$property = $defaultValue;
                    }
                }
            };

        }
    }

    /**
     *
     */
    public function __clone()
    {
        $this->__cloner__ && $this->__cloner__->__invoke($this, '__clone', []);
    }

    /**
     * Forces initialization of the proxy
     */
    public function __load(): void
    {
        $this->__initializer__ && $this->__initializer__->__invoke($this, '__load', []);
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __isInitialized(): bool
    {
        return $this->__isInitialized__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitialized($initialized): void
    {
        $this->__isInitialized__ = $initialized;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitializer(\Closure $initializer = null): void
    {
        $this->__initializer__ = $initializer;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __getInitializer(): ?\Closure
    {
        return $this->__initializer__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setCloner(\Closure $cloner = null): void
    {
        $this->__cloner__ = $cloner;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific cloning logic
     */
    public function __getCloner(): ?\Closure
    {
        return $this->__cloner__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     * @deprecated no longer in use - generated code now relies on internal components rather than generated public API
     * @static
     */
    public function __getLazyProperties(): array
    {
        return self::$lazyPropertiesDefaults;
    }


    /**
     * {@inheritDoc}
     */
    public function getLocale()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLocale', []);

        return parent::getLocale();
    }

    /**
     * {@inheritDoc}
     */
    public function setLocale($locale)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setLocale', [$locale]);

        return parent::setLocale($locale);
    }

    /**
     * {@inheritDoc}
     */
    public function getSite()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSite', []);

        return parent::getSite();
    }

    /**
     * {@inheritDoc}
     */
    public function getSiteType()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSiteType', []);

        return parent::getSiteType();
    }

    /**
     * {@inheritDoc}
     */
    public function getDisplayName()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDisplayName', []);

        return parent::getDisplayName();
    }

    /**
     * {@inheritDoc}
     */
    public function getSiteHomePageID()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSiteHomePageID', []);

        return parent::getSiteHomePageID();
    }

    /**
     * {@inheritDoc}
     */
    public function getSiteHomePageObject($version = 'RECENT')
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSiteHomePageObject', [$version]);

        return parent::getSiteHomePageObject($version);
    }

    /**
     * {@inheritDoc}
     */
    public function setSiteHomePageID($siteHomePageID)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSiteHomePageID', [$siteHomePageID]);

        return parent::setSiteHomePageID($siteHomePageID);
    }

    /**
     * {@inheritDoc}
     */
    public function getSiteTreeID()
    {
        if ($this->__isInitialized__ === false) {
            return (int)  parent::getSiteTreeID();
        }


        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSiteTreeID', []);

        return parent::getSiteTreeID();
    }

    /**
     * {@inheritDoc}
     */
    public function setSiteTreeID($siteTreeID)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSiteTreeID', [$siteTreeID]);

        return parent::setSiteTreeID($siteTreeID);
    }

    /**
     * {@inheritDoc}
     */
    public function getSiteTreeObject()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSiteTreeObject', []);

        return parent::getSiteTreeObject();
    }

}
