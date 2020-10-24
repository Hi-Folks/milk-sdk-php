<?php

namespace HiFolks\Milk\Here\Xyz\Space;

use HiFolks\Milk\Here\Xyz\Common\XyzClient;
use HiFolks\Milk\Here\Xyz\Common\XyzConfig;
use HiFolks\Milk\Here\Xyz\Common\XyzResponse;

/**
 * Class XyzSpace
 * @package HiFolks\Milk\Here\Xyz\Space
 */
class XyzSpace extends XyzClient
{
    /**
     * @var bool
     */
    private $paramIncludeRights = false;
    /**
     * @var string
     */
    private $paramOwner = "";
    /**
     * @var string
     */
    private $paramOwnerId = "";
    /**
     * @var null|int
     */
    private $paramLimit = null;

    /**
     *
     */
    public const PARAM_OWNER_ME = "me";
    public const PARAM_OWNER_ID = "someother";
    public const PARAM_OWNER_OTHERS = "others";
    public const PARAM_OWNER_ALL = "*";


    /**
     * XyzSpace constructor.
     * @param XyzConfig|string|null $c
     */
    public function __construct($c = null)
    {
        parent::__construct($c);
        $this->reset();
    }

    /**
     * @param string $xyzToken
     * @return XyzSpace
     */
    public static function instance($xyzToken = ""): self
    {
        return new XyzSpace(new XyzConfig($xyzToken));
    }


    /**
     *
     */
    public function reset()
    {
        parent::reset();

        $this->paramIncludeRights = false;
        $this->paramOwner = "";
        $this->paramOwnerId = "";
        $this->spaceId = "";
        $this->paramLimit = null;
    }


    /**
     * @param string $spaceId
     * @param mixed $obj
     * @return XyzResponse
     */
    public function update(string $spaceId, $obj)
    {
        $this->httpPatch();
        $this->spaceId($spaceId);
        $this->setType(self::API_TYPE_SPACEUPDATE);
        $this->requestBody = json_encode($obj);
        return  $this->getResponse();
    }

    /**
     * @param string $title
     * @param string $description
     * @return XyzResponse|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function create(string $title, string $description)
    {
        $this->httpPost();
        $this->setType(self::API_TYPE_SPACECREATE);
        $this->requestBody = json_encode((object) [
            'title' => $title,
            'description' => $description
        ]);
        return  $this->getResponse();
    }

    /**
     * @param string $spaceId
     * @return XyzResponse
     */
    public function delete(string $spaceId)
    {
        $this->httpDelete();
        $this->setType(self::API_TYPE_SPACEDELETE);
        $this->spaceId($spaceId);
        return  $this->getResponse();
    }
    /**
     * The access rights for each space are included in the response.
     * @return $this
     */
    public function includeRights(): self
    {
        $this->paramIncludeRights = true;
        return $this;
    }


    /**
     * Set limit of response.
     * NOT IMPLEMENTED BY API, use getLimited instead.
     * @param int $limit
     * @return $this
     */
    public function limit(int $limit): self
    {
        $this->paramLimit = $limit;
        return $this;
    }

    /**
     * Define the owner(s) of spaces to be shown in the response.
     * @param string $owner (me|*|<ownerid>)
     * @param string $ownerId
     * @return $this
     */
    public function owner($owner = self::PARAM_OWNER_ME, $ownerId = ""): XyzSpace
    {
        if ($owner === self::PARAM_OWNER_ID) {
            $this->paramOwner = $owner;
            $this->paramOwnerId = $ownerId;
        } else {
            $this->paramOwner = $owner;
            $this->paramOwnerId = "";
        }
        return $this;
    }

    /**
     * Show only the spaces being owned by the current user.
     * @return $this
     */
    public function ownerMe(): XyzSpace
    {
        return $this->owner(self::PARAM_OWNER_ME);
    }

    /**
     * Only for shared spaces: Explicitly only show spaces belonging to the specified user.
     * @param string $ownerId
     * @return $this
     */
    public function ownerSomeOther(string $ownerId): XyzSpace
    {
        return $this->owner(self::PARAM_OWNER_ID, $ownerId);
    }

    /**
     * Show only the spaces having been shared excluding the own ones.
     */
    public function ownerOthers(): XyzSpace
    {
        return $this->owner(self::PARAM_OWNER_OTHERS);
    }

    /**
     * Show all spaces the current user has access to.
     * @return $this
     */
    public function ownerAll(): XyzSpace
    {
        return $this->owner(self::PARAM_OWNER_ALL);
    }

    /**
     * Set the space id in the API
     * @param string $id
     * @return $this
     */
    public function spaceId(string $id): XyzSpace
    {
        $this->spaceId = $id;
        $this->setType(self::API_TYPE_SPACEDETAIL);
        return $this;
    }


    /**
     * @return string
     */
    protected function queryString(): string
    {
        $retString = "";

        if ($this->paramIncludeRights) {
            $retString = $this->addQueryParam($retString, "includeRights", "true");
        }

        /* not used */
        if ($this->paramLimit) {
            $retString = $this->addQueryParam($retString, "limit", $this->paramLimit);
        }

        if ($this->paramOwner != "") {
            if ($this->paramOwner !== self::PARAM_OWNER_ID) {
                $retString = $this->addQueryParam($retString, "owner", $this->paramOwner);
            } else {
                $retString = $this->addQueryParam($retString, "owner", $this->paramOwnerId);
            }
        }

        //$retString = $this->addQueryParam($retString, "access_token", $this->c->getCredentials()->getAccessToken());

        return $retString;
    }


    /**
     * @param int $limit
     * @return mixed[]
     */
    public function getLimited(int $limit)
    {
        /** @var XyzResponse $response */
        $response = $this->get();
        if ($response->isError()) {
            return [];
        }
        $array = $response->getData();
        return array_slice($array, 0, $limit);
    }
}
