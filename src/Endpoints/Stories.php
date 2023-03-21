<?php

namespace Riclep\StoryblokCli\Endpoints;

use Riclep\StoryblokCli\Data\StoriesData;

class Stories extends BasicEndpoint
{
    public function __construct($token)
    {
        parent::__construct($token);
    }

    public static function make($token = null): self
    {
        return new self(parent::getToken($token));
    }

    public function byId($storyId): StoriesData
    {
        return new StoriesData(
            $this->client->get('spaces/'.$this->spaceId.'/stories/' . $storyId)->getBody()
        );
    }

	public function all(): StoriesData
	{
		return new StoriesData(
			$this->client->get('spaces/'.$this->spaceId.'/stories')->getBody()
		);
	}

	public function bySlug($slug, $withContent = true): StoriesData
	{
		$story = $this->client->get('spaces/'.$this->spaceId.'/stories/', [
			'with_slug' => $slug
		])->getBody();

		if (!$withContent || !$story['stories']) {
			return new StoriesData($story);
		}

		if ($story['stories']) {
			return $this->byId($story['stories'][0]['id']);
		}
	}

	public function search($search): StoriesData
	{
		return new StoriesData(
			$this->client->get('spaces/'.$this->spaceId.'/stories/', [
				'text_search' => urlencode($search)
			])->getBody()
		);
	}

	public function create($story): StoriesData {
		return new StoriesData(
			$this->client->post('spaces/'.$this->spaceId.'/stories', $story)->getBody()
		);
	}

	public function update($id, $story): StoriesData {
		return new StoriesData(
			$this->client->put('spaces/'.$this->spaceId.'/stories/' . $id, $story)->getBody()
		);
	}

	public function delete($id) {
		return new StoriesData(
			$this->client->delete('spaces/'.$this->spaceId.'/stories/' . $id)->getBody()
		);
	}

	public function publish($id): StoriesData {
		return new StoriesData(
			$this->client->get('spaces/'.$this->spaceId.'/stories/' . $id . '/publish')->getBody()
		);
	}

	public function unpublish($id): StoriesData {
		return new StoriesData(
			$this->client->get('spaces/'.$this->spaceId.'/stories/' . $id . '/unpublish')->getBody()
		);
	}
}
