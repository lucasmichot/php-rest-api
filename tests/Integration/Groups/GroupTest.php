<?php

namespace Tests\Integration\Groups;

use Tests\Integration\BaseTest;

class GroupTest extends BaseTest
{
    public function testCreateGroup()
    {
        $group             = new \MessageBird\Objects\Group();
        $group->name  = "Home";


        $this->mockClient->expects($this->once())->method('performHttpRequest')->willReturn([200, '', '{
            "id": "61afc0531573b08ddbe36e1c85602827",
            "href": "https://rest.messagebird.com/groups/61afc0531573b08ddbe36e1c85602827",
            "name": "Home",
            "contacts": {
              "totalCount": 0,
              "href": "https://rest.messagebird.com/groups/61afc0531573b08ddbe36e1c85602827/contacts"
            },
            "createdDatetime": "2016-04-29T09:42:26+00:00",
            "updatedDatetime": "2016-04-29T09:42:26+00:00"
        }']);
        $this->client->groups->create($group);
    }

    public function testListGroups()
    {
        $this->expectException(\MessageBird\Exceptions\ServerException::class);
        $this->mockClient->expects($this->once())->method('performHttpRequest')->with("GET", 'groups', ['offset' => 100, 'limit' => 30], null);
        $this->client->groups->getList(['offset' => 100, 'limit' => 30]);
    }

    public function testViewGroup()
    {
        $this->expectException(\MessageBird\Exceptions\ServerException::class);
        $this->mockClient->expects($this->once())->method('performHttpRequest')->with("GET", 'groups/group_id', null, null);
        $this->client->groups->read("group_id");
    }

    public function testDeleteGroup()
    {
        $this->expectException(\MessageBird\Exceptions\ServerException::class);
        $this->mockClient->expects($this->once())->method('performHttpRequest')->with("DELETE", 'groups/group_id', null, null);
        $this->client->groups->delete("group_id");
    }
}
