<?php

namespace DoctrineProxies\__CG__\Concrete\Core\Entity\Express\Control;


/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class PublicIdentifierControl extends \Concrete\Core\Entity\Express\Control\PublicIdentifierControl implements \Doctrine\ORM\Proxy\Proxy
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
            return ['__isInitialized__', 'id', 'is_required', 'position', 'custom_label', 'field_set'];
        }

        return ['__isInitialized__', 'id', 'is_required', 'position', 'custom_label', 'field_set'];
    }

    /**
     *
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (PublicIdentifierControl $proxy) {
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
    public function getControlLabel()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getControlLabel', []);

        return parent::getControlLabel();
    }

    /**
     * {@inheritDoc}
     */
    public function getControlView(\Concrete\Core\Form\Context\ContextInterface $context)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getControlView', [$context]);

        return parent::getControlView($context);
    }

    /**
     * {@inheritDoc}
     */
    public function getType()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getType', []);

        return parent::getType();
    }

    /**
     * {@inheritDoc}
     */
    public function getExporter()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getExporter', []);

        return parent::getExporter();
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
    public function getCustomLabel()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCustomLabel', []);

        return parent::getCustomLabel();
    }

    /**
     * {@inheritDoc}
     */
    public function setCustomLabel($custom_label)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCustomLabel', [$custom_label]);

        return parent::setCustomLabel($custom_label);
    }

    /**
     * {@inheritDoc}
     */
    public function getPosition()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPosition', []);

        return parent::getPosition();
    }

    /**
     * {@inheritDoc}
     */
    public function setPosition($position)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPosition', [$position]);

        return parent::setPosition($position);
    }

    /**
     * {@inheritDoc}
     */
    public function getFieldSet()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getFieldSet', []);

        return parent::getFieldSet();
    }

    /**
     * {@inheritDoc}
     */
    public function setFieldSet($field_set)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setFieldSet', [$field_set]);

        return parent::setFieldSet($field_set);
    }

    /**
     * {@inheritDoc}
     */
    public function getControlOptionsController()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getControlOptionsController', []);

        return parent::getControlOptionsController();
    }

    /**
     * {@inheritDoc}
     */
    public function getControlSaveHandler()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getControlSaveHandler', []);

        return parent::getControlSaveHandler();
    }

    /**
     * {@inheritDoc}
     */
    public function jsonSerialize()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'jsonSerialize', []);

        return parent::jsonSerialize();
    }

    /**
     * {@inheritDoc}
     */
    public function isRequired()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'isRequired', []);

        return parent::isRequired();
    }

    /**
     * {@inheritDoc}
     */
    public function setIsRequired($is_required)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setIsRequired', [$is_required]);

        return parent::setIsRequired($is_required);
    }

    /**
     * {@inheritDoc}
     */
    public function getDisplayLabel()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDisplayLabel', []);

        return parent::getDisplayLabel();
    }

    /**
     * {@inheritDoc}
     */
    public function getControlType()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getControlType', []);

        return parent::getControlType();
    }

    /**
     * {@inheritDoc}
     */
    public function build(\Concrete\Core\Express\ObjectBuilder $builder)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'build', [$builder]);

        return parent::build($builder);
    }

}
