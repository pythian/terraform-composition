<?php

namespace DoctrineProxies\__CG__\Concrete\Core\Entity\Board\DataSource;


/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class ConfiguredDataSource extends \Concrete\Core\Entity\Board\DataSource\ConfiguredDataSource implements \Doctrine\ORM\Proxy\Proxy
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
            return ['__isInitialized__', 'configuredDataSourceID', 'board', 'populationDayIntervalFuture', 'populationDayIntervalPast', 'data_source', 'items', 'name', 'customWeight', 'configuration'];
        }

        return ['__isInitialized__', 'configuredDataSourceID', 'board', 'populationDayIntervalFuture', 'populationDayIntervalPast', 'data_source', 'items', 'name', 'customWeight', 'configuration'];
    }

    /**
     *
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (ConfiguredDataSource $proxy) {
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
    public function getBoard(): ?\Concrete\Core\Entity\Board\Board
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getBoard', []);

        return parent::getBoard();
    }

    /**
     * {@inheritDoc}
     */
    public function getConfiguration(): ?\Concrete\Core\Entity\Board\DataSource\Configuration\Configuration
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getConfiguration', []);

        return parent::getConfiguration();
    }

    /**
     * {@inheritDoc}
     */
    public function setBoard(\Concrete\Core\Entity\Board\Board $board): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setBoard', [$board]);

        parent::setBoard($board);
    }

    /**
     * {@inheritDoc}
     */
    public function setDataSource($data_source): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDataSource', [$data_source]);

        parent::setDataSource($data_source);
    }

    /**
     * {@inheritDoc}
     */
    public function getConfiguredDataSourceID()
    {
        if ($this->__isInitialized__ === false) {
            return (int)  parent::getConfiguredDataSourceID();
        }


        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getConfiguredDataSourceID', []);

        return parent::getConfiguredDataSourceID();
    }

    /**
     * {@inheritDoc}
     */
    public function getDataSource()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDataSource', []);

        return parent::getDataSource();
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getName', []);

        return parent::getName();
    }

    /**
     * {@inheritDoc}
     */
    public function setName($name): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setName', [$name]);

        parent::setName($name);
    }

    /**
     * {@inheritDoc}
     */
    public function getCustomWeight()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCustomWeight', []);

        return parent::getCustomWeight();
    }

    /**
     * {@inheritDoc}
     */
    public function setCustomWeight($customWeight): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCustomWeight', [$customWeight]);

        parent::setCustomWeight($customWeight);
    }

    /**
     * {@inheritDoc}
     */
    public function getPopulationDayIntervalFuture(): int
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPopulationDayIntervalFuture', []);

        return parent::getPopulationDayIntervalFuture();
    }

    /**
     * {@inheritDoc}
     */
    public function setPopulationDayIntervalFuture(int $populationDayIntervalFuture): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPopulationDayIntervalFuture', [$populationDayIntervalFuture]);

        parent::setPopulationDayIntervalFuture($populationDayIntervalFuture);
    }

    /**
     * {@inheritDoc}
     */
    public function getPopulationDayIntervalPast(): int
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPopulationDayIntervalPast', []);

        return parent::getPopulationDayIntervalPast();
    }

    /**
     * {@inheritDoc}
     */
    public function setPopulationDayIntervalPast(int $populationDayIntervalPast): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPopulationDayIntervalPast', [$populationDayIntervalPast]);

        parent::setPopulationDayIntervalPast($populationDayIntervalPast);
    }

    /**
     * {@inheritDoc}
     */
    public function getItems()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getItems', []);

        return parent::getItems();
    }

}
