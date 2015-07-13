<?php

namespace Zendesk\API\Resources;

use Zendesk\API\Traits\Resource\Defaults;
use Zendesk\API\Traits\Utility\InstantiatorTrait;

/**
 * The Requests class exposes request management methods
 *
 * @method RequestComments comments()
 */
class Requests extends ResourceAbstract
{
    const OBJ_NAME = 'request';
    const OBJ_NAME_PLURAL = 'requests';

    use InstantiatorTrait;

    use Defaults;

    /**
     * {@inheritdoc}
     */
    protected function setUpRoutes()
    {
        $this->setRoutes([
            'findAllOpen'   => "{$this->resourceName}/open.json",
            'findAllSolved' => "{$this->resourceName}/solved.json",
            'findAllCCd'    => "{$this->resourceName}/ccd.json",
            'search'        => "{$this->resourceName}/search.json",
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public static function getValidSubResources()
    {
        return [
            'comments' => RequestComments::class,
        ];
    }

    /**
     * {$@inheritdoc}
     */
    public function getRoute($name, array $params = [])
    {
        $lastChained = $this->getLatestChainedParameter([self::class]);

        if ((empty($lastChained)) || ! (in_array($name, ['findAll']))) {
            return parent::getRoute($name, $params);
        }

        $chainedResourceId    = reset($lastChained);
        $chainedResourceNames = array_keys($lastChained);
        $chainedResourceName  = (new $chainedResourceNames[0]($this->client))->resourceName;

        if ($name === 'findAll') {
            if (in_array($chainedResourceName, ['users', 'organizations'])) {
                return "{$chainedResourceName}/{$chainedResourceId}/{$this->resourceName}.json";
            }

            return "{$this->resourceName}.json";
        }
    }

    /**
     * Find all open requests
     *
     * @param array $params
     */
    public function findAllOpen(array $params = [])
    {
        $this->findAll($params, __FUNCTION__);
    }

    /**
     * Find all open requests
     *
     * @param array $params
     */
    public function findAllSolved(array $params = [])
    {
        $this->findAll($params, __FUNCTION__);
    }

    /**
     * Find all open requests
     *
     * @param array $params
     */
    public function findAllCCd(array $params = [])
    {
        $this->findAll($params, __FUNCTION__);
    }

    /**
     * Searching requests
     *
     * @param array $queryParams
     *
     * @return array
     */
    public function search(array $queryParams)
    {
        return $this->client->get($this->getRoute(__FUNCTION__), $queryParams);
    }
}
