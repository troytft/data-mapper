# Data Mapper Bundle

## Installation
Install using Composer:

```
composer require troytft/data-mapper-bundle
```

Add the bundle to your AppKernel.php:

``` php
$bundles = array(
    // ...
    new Troytft\DataMapperBundle\DataMapperBundle(),
);
```

## Usage
Model:

``` php
<?php
namespace Common\Model;

use Common\Constraint as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;
use Troytft\DataMapperBundle\Annotation\DataMapper;

class PostsFilter
{
    /**
     * @DataMapper(type="string")
     */
    protected $query;

    /**
     * @DataMapper(type="entity", options={"class": "CommonBundle:City"})
     * @Assert\NotBlank
     */
    protected $city;

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $value
     */
    public function setCity($value)
    {
        $this->city = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param string $value
     */
    public function setQuery($value)
    {
        $this->query = $value;
        
        return $this;
    }
}
```

Controller:
``` php
    public function handleRequest($model, $clearMissing = true, $groups = ['Default'], $validationGroups = ['Default'])
    {
        /** @var Request $request */
        $request = $this->get('request');
        $data = $request->getRealMethod() == 'GET' ? $request->query->all() : $request->request->all();

        /** @var DataMapperManager $manager */
        $manager = $this->get('data_mapper.manager');

        return $manager
            ->setGroups($groups)
            ->setValidationGroups($validationGroups)
            ->setIsClearMissing($clearMissing)
            ->handle($model, $data);
    }
```