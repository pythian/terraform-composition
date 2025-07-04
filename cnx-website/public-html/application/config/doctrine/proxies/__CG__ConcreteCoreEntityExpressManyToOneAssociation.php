<?php

namespace DoctrineProxies\__CG__\Concrete\Core\Entity\Express;


/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class ManyToOneAssociation extends \Concrete\Core\Entity\Express\ManyToOneAssociation implements \Doctrine\ORM\Proxy\Proxy
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
            return ['__isInitialized__', 'id', 'source_entity', 'target_entity', 'is_owned_by_association', 'is_owning_association', 'entry', 'controls', 'target_property_name', 'inversed_by_property_name'];
        }

        return ['__isInitialized__', 'id', 'source_entity', 'target_entity', 'is_owned_by_association', 'is_owning_association', 'entry', 'controls', 'target_property_name', 'inversed_by_property_name'];
    }

    /**
     *
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (ManyToOneAssociation $proxy) {
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
    public function getAssociationBuilder()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getAssociationBuilder', []);

        return parent::getAssociationBuilder();
    }

    /**
     * {@inheritDoc}
     */
    public function getFormatter()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getFormatter', []);

        return parent::getFormatter();
    }

    /**
     * {@inheritDoc}
     */
    public function getSaveHandler()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSaveHandler', []);

        return parent::getSaveHandler();
    }

    /**
     * {@inheritDoc}
     */
    public function getId()
    {
        if ($this->__isInitialized__ === false) {
            return  parent::getId();
        }


        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getId', []);

        return parent::getId();
    }

    /**
     * {@inheritDoc}
     */
    public function setId($id)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setId', [$id]);

        return parent::setId($id);
    }

    /**
     * {@inheritDoc}
     */
    public function isOwningAssociation()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'isOwningAssociation', []);

        return parent::isOwningAssociation();
    }

    /**
     * {@inheritDoc}
     */
    public function setIsOwningAssociation($is_owning_association)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setIsOwningAssociation', [$is_owning_association]);

        return parent::setIsOwningAssociation($is_owning_association);
    }

    /**
     * {@inheritDoc}
     */
    public function isOwnedByAssociation()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'isOwnedByAssociation', []);

        return parent::isOwnedByAssociation();
    }

    /**
     * {@inheritDoc}
     */
    public function setIsOwnedByAssociation($is_owned_by_association)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setIsOwnedByAssociation', [$is_owned_by_association]);

        return parent::setIsOwnedByAssociation($is_owned_by_association);
    }

    /**
     * {@inheritDoc}
     */
    public function getTargetPropertyName()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTargetPropertyName', []);

        return parent::getTargetPropertyName();
    }

    /**
     * {@inheritDoc}
     */
    public function setTargetPropertyName($target_property_name)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTargetPropertyName', [$target_property_name]);

        return parent::setTargetPropertyName($target_property_name);
    }

    /**
     * {@inheritDoc}
     */
    public function getInversedByPropertyName()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getInversedByPropertyName', []);

        return parent::getInversedByPropertyName();
    }

    /**
     * {@inheritDoc}
     */
    public function setInversedByPropertyName($inversed_by_property_name)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setInversedByPropertyName', [$inversed_by_property_name]);

        return parent::setInversedByPropertyName($inversed_by_property_name);
    }

    /**
     * {@inheritDoc}
     */
    public function getSourceEntity()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSourceEntity', []);

        return parent::getSourceEntity();
    }

    /**
     * {@inheritDoc}
     */
    public function setSourceEntity($source_entity)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSourceEntity', [$source_entity]);

        return parent::setSourceEntity($source_entity);
    }

    /**
     * {@inheritDoc}
     */
    public function getTargetEntity()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTargetEntity', []);

        return parent::getTargetEntity();
    }

    /**
     * {@inheritDoc}
     */
    public function setTargetEntity($target_entity)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTargetEntity', [$target_entity]);

        return parent::setTargetEntity($target_entity);
    }

    /**
     * {@inheritDoc}
     */
    public function getComputedTargetPropertyName()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getComputedTargetPropertyName', []);

        return parent::getComputedTargetPropertyName();
    }

    /**
     * {@inheritDoc}
     */
    public function getComputedInversedByPropertyName()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getComputedInversedByPropertyName', []);

        return parent::getComputedInversedByPropertyName();
    }

    /**
     * {@inheritDoc}
     */
    public function getExporter()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getExporter', []);

        return parent::getExporter();
    }

}
