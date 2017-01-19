<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\base;

use Yii;

/**
 * Object is the base class that implements the *property* feature.
 * Object是一个实现了 属性 特性的基类
 *
 * 一个属性通过getter setter定义
 * A property is defined by a getter method (e.g. `getLabel`), and/or a setter method (e.g. `setLabel`). For example,
 * the following getter and setter methods define a property named `label`:
 *
 *
 * ```php
 * private $_label;
 *
 * public function getLabel()
 * {
 *     return $this->_label;
 * }
 *
 * public function setLabel($value)
 * {
 *     $this->_label = $value;
 * }
 * ```
 *
 * 属性名不分大小写
 * Property names are *case-insensitive*.
 *属性可以看成是对象的成员变量，通过getter setter节点获取或者设置属性
 * A property can be accessed like a member variable of an object. Reading or writing a property will cause the invocation
 * of the corresponding getter or setter method. For example,
 *
 * ```php
 * // equivalent to $label = $object->getLabel();
 * $label = $object->label;
 * // equivalent to $object->setLabel('abc');
 * $object->label = 'abc';
 * ```
 *如果一个属性只有getter 表明这个属性只读，尝试修改这个属性会抛出异常
 * If a property has only a getter method and has no setter method, it is considered as *read-only*. In this case, trying
 * to modify the property value will cause an exception.
 *可以调用hasProperty 或者 canGetProperty canSetProperty 来校验一个属性是否存在
 * One can call [[hasProperty()]], [[canGetProperty()]] and/or [[canSetProperty()]] to check the existence of a property.
 *除了属性特性之外，对象引进了重要的对象初始化生命周期。特别的，创建一个新的对象实例或者派生类将会循环如下生命周期
 * Besides the property feature, Object also introduces an important object initialization life cycle. In particular,
 * creating an new instance of Object or its derived class will involve the following life cycles sequentially:
 *
 * 1. the class constructor is invoked;
 * 类的构造函数被唤醒
 * 2. object properties are initialized according to the given configuration;
 * 根据给定的配置初始化对象属性
 * 3. the `init()` method is invoked.
 *init方法被唤醒
 *
 * 上面的第2步和第3步发生在类的构造函数结束之后，建议对象的初始阶段在init()执行，在那个阶段对象的配置已经完成
 * In the above, both Step 2 and 3 occur at the end of the class constructor. It is recommended that
 * you perform object initialization in the `init()` method because at that stage, the object configuration
 * is already applied.
 *
 * 为了确保上诉生命周期，如果一个Object的子类需要重写构造函数应该如下：
 * In order to ensure the above life cycles, if a child class of Object needs to override the constructor,
 * it should be done like the following:
 *
 * ```php
 * public function __construct($param1, $param2, ..., $config = [])
 * {
 *     ...
 *     parent::__construct($config);
 * }
 * ```
 *$config 默认为空数组，应该在最后一个参数被声明
 * That is, a `$config` parameter (defaults to `[]`) should be declared as the last parameter
 * of the constructor, and the parent implementation should be called at the end of the constructor.
 *父类的构造函数最后也要传递$config
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class Object implements Configurable
{
    /**
     * Returns the fully qualified name of this class.
     * @return string the fully qualified name of this class.
     */
    public static function className()
    {
        return get_called_class();
    }

    /**
     * Constructor.
     * 构造函数 默认执行如下2件事
     * The default implementation does two things:
     *根据给定的$config 初始化配置
     * - Initializes the object with the given configuration `$config`.
     * - Call [[init()]].
     *调用init函数
     * If this method is overridden in a child class, it is recommended that
     *如果该方法被重写，建议构造函数的最后一个值是一个$config的数组，父类的构造函数最后传递$config
     * - the last parameter of the constructor is a configuration array, like `$config` here.
     * - call the parent implementation at the end of the constructor.
     *
     * @param array $config name-value pairs that will be used to initialize the object properties
     */
    public function __construct($config = [])
    {
        tracelog(__METHOD__);
        if (!empty($config)) {
            tracelog('配置对象属性');
            Yii::configure($this, $config);
        }
        $this->init();
    }

    /**
     * Initializes the object.
     * 对象初始化
     * 对象构造函数结束之后唤醒初始化方法
     * This method is invoked at the end of the constructor after the object is initialized with the
     * given configuration.
     */
    public function init()
    {
    }

    /**
     * 返回对象属性值
     * Returns the value of an object property.
     *
     * Do not call this method directly as it is a PHP magic method that
     * will be implicitly called when executing `$value = $object->property;`.
     * @param string $name the property name
     * @return mixed the property value
     * @throws UnknownPropertyException if the property is not defined
     * @throws InvalidCallException if the property is write-only
     * @see __set()
     */
    public function __get($name)
    {
        $getter = 'get' . $name;
        if (method_exists($this, $getter)) {
            return $this->$getter();
        } elseif (method_exists($this, 'set' . $name)) {
            throw new InvalidCallException('Getting write-only property: ' . get_class($this) . '::' . $name);
        } else {
            throw new UnknownPropertyException('Getting unknown property: ' . get_class($this) . '::' . $name);
        }
    }

    /**
     * 设置对象属性值
     * Sets value of an object property.
     *
     * Do not call this method directly as it is a PHP magic method that
     * will be implicitly called when executing `$object->property = $value;`.
     * @param string $name the property name or the event name
     * @param mixed $value the property value
     * @throws UnknownPropertyException if the property is not defined
     * @throws InvalidCallException if the property is read-only
     * @see __get()
     */
    public function __set($name, $value)
    {
        $setter = 'set' . $name;
        if (method_exists($this, $setter)) {
            $this->$setter($value);
        } elseif (method_exists($this, 'get' . $name)) {
            throw new InvalidCallException('Setting read-only property: ' . get_class($this) . '::' . $name);
        } else {
            throw new UnknownPropertyException('Setting unknown property: ' . get_class($this) . '::' . $name);
        }
    }

    /**
     * Checks if a property is set, i.e. defined and not null.
     *
     * Do not call this method directly as it is a PHP magic method that
     * will be implicitly called when executing `isset($object->property)`.
     *
     * Note that if the property is not defined, false will be returned.
     * @param string $name the property name or the event name
     * @return boolean whether the named property is set (not null).
     * @see http://php.net/manual/en/function.isset.php
     */
    public function __isset($name)
    {
        $getter = 'get' . $name;
        if (method_exists($this, $getter)) {
            return $this->$getter() !== null;
        } else {
            return false;
        }
    }

    /**
     * Sets an object property to null.
     *
     * Do not call this method directly as it is a PHP magic method that
     * will be implicitly called when executing `unset($object->property)`.
     *
     * Note that if the property is not defined, this method will do nothing.
     * If the property is read-only, it will throw an exception.
     * @param string $name the property name
     * @throws InvalidCallException if the property is read only.
     * @see http://php.net/manual/en/function.unset.php
     */
    public function __unset($name)
    {
        $setter = 'set' . $name;
        if (method_exists($this, $setter)) {
            $this->$setter(null);
        } elseif (method_exists($this, 'get' . $name)) {
            throw new InvalidCallException('Unsetting read-only property: ' . get_class($this) . '::' . $name);
        }
    }

    /**
     * Calls the named method which is not a class method.
     *
     * Do not call this method directly as it is a PHP magic method that
     * will be implicitly called when an unknown method is being invoked.
     * @param string $name the method name
     * @param array $params method parameters
     * @throws UnknownMethodException when calling unknown method
     * @return mixed the method return value
     */
    public function __call($name, $params)
    {
        throw new UnknownMethodException('Calling unknown method: ' . get_class($this) . "::$name()");
    }

    /**
     * Returns a value indicating whether a property is defined.
     * A property is defined if:
     *
     * - the class has a getter or setter method associated with the specified name
     *   (in this case, property name is case-insensitive);
     * - the class has a member variable with the specified name (when `$checkVars` is true);
     *
     * @param string $name the property name
     * @param boolean $checkVars whether to treat member variables as properties
     * @return boolean whether the property is defined
     * @see canGetProperty()
     * @see canSetProperty()
     */
    public function hasProperty($name, $checkVars = true)
    {
        return $this->canGetProperty($name, $checkVars) || $this->canSetProperty($name, false);
    }

    /**
     * Returns a value indicating whether a property can be read.
     * A property is readable if:
     *
     * - the class has a getter method associated with the specified name
     *   (in this case, property name is case-insensitive);
     * - the class has a member variable with the specified name (when `$checkVars` is true);
     *
     * @param string $name the property name
     * @param boolean $checkVars whether to treat member variables as properties
     * @return boolean whether the property can be read
     * @see canSetProperty()
     */
    public function canGetProperty($name, $checkVars = true)
    {
        return method_exists($this, 'get' . $name) || $checkVars && property_exists($this, $name);
    }

    /**
     * Returns a value indicating whether a property can be set.
     * A property is writable if:
     *
     * - the class has a setter method associated with the specified name
     *   (in this case, property name is case-insensitive);
     * - the class has a member variable with the specified name (when `$checkVars` is true);
     *
     * @param string $name the property name
     * @param boolean $checkVars whether to treat member variables as properties
     * @return boolean whether the property can be written
     * @see canGetProperty()
     */
    public function canSetProperty($name, $checkVars = true)
    {
        return method_exists($this, 'set' . $name) || $checkVars && property_exists($this, $name);
    }

    /**
     * Returns a value indicating whether a method is defined.
     *
     * The default implementation is a call to php function `method_exists()`.
     * You may override this method when you implemented the php magic method `__call()`.
     * @param string $name the method name
     * @return boolean whether the method is defined
     */
    public function hasMethod($name)
    {
        return method_exists($this, $name);
    }
}
