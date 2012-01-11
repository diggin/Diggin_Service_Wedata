<?php

namespace Diggin\Service\Wedata;

/**
 * Diggin\Service\Wedata\Database
 *
 * @Table(name="wedata_database")
 * @Entity
 */
class Database
{
    /**
     * @var string $name
     *
     * @Column(name="name", type="string", length=200, precision=0, scale=0, nullable=false, unique=false)
     * @Id
     * @GeneratedValue(strategy="NONE")
     */
    private $name;

    /**
     * @var string $updated_at
     *
     * @Column(name="updated_at", type="string", length=200, precision=0, scale=0, nullable=false, unique=false)
     */
    private $updated_at;

    /**
     * @var string $optional_keys
     *
     * @Column(name="optional_keys", type="string", length=200, precision=0, scale=0, nullable=false, unique=false)
     */
    private $optional_keys;

    /**
     * @var string $required_keys
     *
     * @Column(name="required_keys", type="string", length=200, precision=0, scale=0, nullable=false, unique=false)
     */
    private $required_keys;

    /**
     * @var string $created_by
     *
     * @Column(name="created_by", type="string", length=200, precision=0, scale=0, nullable=false, unique=false)
     */
    private $created_by;

    /**
     * @var string $description
     *
     * @Column(name="description", type="string", length=200, precision=0, scale=0, nullable=false, unique=false)
     */
    private $description;

    /**
     * @var string $permit_other_keys
     *
     * @Column(name="permit_other_keys", type="string", length=200, precision=0, scale=0, nullable=false, unique=false)
     */
    private $permit_other_keys;

    /**
     * @var string $resource_url
     *
     * @Column(name="resource_url", type="string", length=200, precision=0, scale=0, nullable=false, unique=false)
     */
    private $resource_url;

    /**
     * @var string $created_at
     *
     * @Column(name="created_at", type="string", length=200, precision=0, scale=0, nullable=false, unique=false)
     */
    private $created_at;

    public function __construct($data)
    {
        $setter = function($key) {
            return "set".preg_replace(array('#('.preg_quote('_').')([A-Za-z]{1})#e','#(^[A-Za-z]{1})#e'), array("strtoupper('\\2')","strtoupper('\\1')"), $key);
        };

        foreach ($data as $k => $v) {
            $this->{$setter($k)}($v);
        }
    }

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set updated_at
     *
     * @param string $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;
    }

    /**
     * Get updated_at
     *
     * @return string $updatedAt
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * Set optional_keys
     *
     * @param string $optionalKeys
     */
    public function setOptionalKeys($optionalKeys)
    {
        $this->optional_keys = $optionalKeys;
    }

    /**
     * Get optional_keys
     *
     * @return string $optionalKeys
     */
    public function getOptionalKeys()
    {
        return $this->optional_keys;
    }

    /**
     * Set required_keys
     *
     * @param string $requiredKeys
     */
    public function setRequiredKeys($requiredKeys)
    {
        $this->required_keys = $requiredKeys;
    }

    /**
     * Get required_keys
     *
     * @return string $requiredKeys
     */
    public function getRequiredKeys()
    {
        return $this->required_keys;
    }

    /**
     * Set created_by
     *
     * @param string $createdBy
     */
    public function setCreatedBy($createdBy)
    {
        $this->created_by = $createdBy;
    }

    /**
     * Get created_by
     *
     * @return string $createdBy
     */
    public function getCreatedBy()
    {
        return $this->created_by;
    }

    /**
     * Set description
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return string $description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set permit_other_keys
     *
     * @param string $permitOtherKeys
     */
    public function setPermitOtherKeys($permitOtherKeys)
    {
        $this->permit_other_keys = $permitOtherKeys;
    }

    /**
     * Get permit_other_keys
     *
     * @return string $permitOtherKeys
     */
    public function getPermitOtherKeys()
    {
        return $this->permit_other_keys;
    }

    /**
     * Set resource_url
     *
     * @param string $resourceUrl
     */
    public function setResourceUrl($resourceUrl)
    {
        $this->resource_url = $resourceUrl;
    }

    /**
     * Get resource_url
     *
     * @return string $resourceUrl
     */
    public function getResourceUrl()
    {
        return $this->resource_url;
    }

    /**
     * Set created_at
     *
     * @param string $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;
    }

    /**
     * Get created_at
     *
     * @return string $createdAt
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function getRequiredKeysAsArray()
    {
        return array_unique(array_filter(preg_split('/ /', $this->getRequiredKeys())));
    }

    public function getOptionalKeysAsArray()
    {
        return array_unique(array_filter(preg_split('/ /', $this->getOptionalKeys())));
    }

    public function toApiArray()
    {
        $array = array();
        foreach (array(
            'name' => 'getName',
            'optional_keys' => 'getOptionalKeys',
            'required_keys' => 'getRequiredKeys',
            'description' => 'getDescription',
            'permit_other_keys' => 'getPermitOtherKeys',
            'resource_url' => 'getResourceUrl'
        ) as $k => $getter) {
            if ($val = $this->$getter()) $array[$k] = $val;
        }

        return $array;
    }
}
