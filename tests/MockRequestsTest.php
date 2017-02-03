<?php

namespace LeanTesting\API\Client\Test;

use LeanTesting\API\Client\Client;

use LeanTesting\API\Client\Entity\Bug\Bug;

use LeanTesting\API\Client\Entity\Platform\PlatformBrowser;
use LeanTesting\API\Client\Entity\Platform\PlatformOS;
use LeanTesting\API\Client\Entity\Platform\PlatformType;

use LeanTesting\API\Client\Entity\Project\Project;

class MockRequestsTest extends \PHPUnit_Framework_TestCase
{
    protected $client;

    protected function setUp() {
        $this->client = new Client;
    }

    protected function rint($min = 100, $max = 9999999) {
        return rand($min, $max);
    }
    protected function rstr($ln = null) {
        if ($ln === null) {
            $ln = $this->rint(1, 15);
        }

        $c = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $s = '';

        for ($i = 0; $i < $ln; $i++) {
            $s .= $c[rand(0, strlen($c) - 1)];
        }

        return $s;
    }
    protected function robj($fields) {
        $obj = [];
        foreach ($fields as $f) {
            if ($f[0] === '_') {
                $obj[substr($f, 1)] = $this->rint();
            } else {
                $obj[$f] = $this->rstr();
            }
        }
        return $obj;
    }
    protected function rcol($name, $fields) {
        $col = [];
        $col[$name] = [];

        for ($i = 0; $i < $this->rint(1, 7); $i++) {
            array_push($col[$name], $this->robj($fields));
        }

        $total_pages = $this->rint(2, 15);
        $count = count($col[$name]);
        $per_page = $count;
        $total = $total_pages * $per_page;

        $col['meta'] = [
            'pagination' => [
                'total' => $total,
                'count' => $count,
                'per_page' => $per_page,
                'current_page' => $this->rint(1, $total_pages),
                'total_pages' => $total_pages,
                'links' => []
            ]
        ];

        return $col;
    }



    /* USER */
    public function testGetUserInformation() {
        $resp = $this->robj(['first_name', 'created_at', '_id', 'last_name', 'avatar', 'email', 'username']);
        $this->client->debug_return = ['data' => json_encode($resp), 'status' => 200];

        $data = $this->client->user->getInformation();

        $this->assertSame($resp, $data);
    }
    public function testGetUserOrganizations() {
        $col_name = 'organizations';
        $ret_class = 'LeanTesting\API\Client\Entity\User\UserOrganization';
        $resp = $this->rcol($col_name, ['alias', '_id', 'logo', 'name', '_owner_id', 'url']);
        $this->client->debug_return = ['data' => json_encode($resp), 'status' => 200];

        $col = $this->client->user->organizations->all();

        $this->assertSame($resp[$col_name], $col->toArray());
        $this->assertInstanceOf($ret_class, $col->collection[0]);
        $this->assertEquals($resp['meta']['pagination']['total'], $col->total());
        $this->assertEquals($resp['meta']['pagination']['total_pages'], $col->totalPages());
        $this->assertEquals($resp['meta']['pagination']['count'], $col->count());
    }
    public function testRetrieveUserOrganizations() {
        $ret_class = 'LeanTesting\API\Client\Entity\User\UserOrganization';
        $resp = $this->robj(['alias', '_id', 'logo', 'name', '_owner_id', 'url']);
        $this->client->debug_return = ['data' => json_encode($resp), 'status' => 200];

        $obj = $this->client->user->organizations->find(0);

        $this->assertSame($resp, $obj->data);
        $this->assertInstanceOf($ret_class, $obj);
    }
    /* END USER */




    /* PROJECT */
    public function testListAllProjects() {
        $col_name = 'projects';
        $ret_class = 'LeanTesting\API\Client\Entity\Project\Project';
        $resp = $this->rcol($col_name, ['_id', 'name', '_owner_id', '_organization_id', '_is_archived', 'created_at']);
        $this->client->debug_return = ['data' => json_encode($resp), 'status' => 200];

        $col = $this->client->projects->all();

        $this->assertSame($resp[$col_name], $col->toArray());
        $this->assertInstanceOf($ret_class, $col->collection[0]);
        $this->assertEquals($resp['meta']['pagination']['total'], $col->total());
        $this->assertEquals($resp['meta']['pagination']['total_pages'], $col->totalPages());
        $this->assertEquals($resp['meta']['pagination']['count'], $col->count());
    }
    public function testCreateNewProject() {
        $ret_class = 'LeanTesting\API\Client\Entity\Project\Project';
        $resp = $this->robj(['_id', 'name', '_owner_id', '_organization_id', '_is_archived', 'created_at']);
        $this->client->debug_return = ['data' => json_encode($resp), 'status' => 200];

        $obj = $this->client->projects->create([
            'name' => '', 'organization_id' => 0
        ]);

        $this->assertSame($resp, $obj->data);
        $this->assertInstanceOf($ret_class, $obj);
    }
    public function testRetrieveExistingProject() {
        $ret_class = 'LeanTesting\API\Client\Entity\Project\Project';
        $resp = $this->robj(['_id', 'name', '_owner_id', '_organization_id', '_is_archived', 'created_at']);
        $this->client->debug_return = ['data' => json_encode($resp), 'status' => 200];

        $obj = $this->client->projects->find(0);

        $this->assertSame($resp, $obj->data);
        $this->assertInstanceOf($ret_class, $obj);
    }


    public function testListProjectSections() {
        $col_name = 'sections';
        $ret_class = 'LeanTesting\API\Client\Entity\Project\ProjectSection';
        $resp = $this->rcol($col_name, ['_id', 'name', '_project_id']);
        $this->client->debug_return = ['data' => json_encode($resp), 'status' => 200];

        $col = (new Project($this->client, ['id' => 0]))->sections->all();

        $this->assertSame($resp[$col_name], $col->toArray());
        $this->assertInstanceOf($ret_class, $col->collection[0]);
        $this->assertEquals($resp['meta']['pagination']['total'], $col->total());
        $this->assertEquals($resp['meta']['pagination']['total_pages'], $col->totalPages());
        $this->assertEquals($resp['meta']['pagination']['count'], $col->count());
    }
    public function testAddProjectSection() {
        $ret_class = 'LeanTesting\API\Client\Entity\Project\ProjectSection';
        $resp = $this->robj(['_id', 'name', '_project_id']);
        $this->client->debug_return = ['data' => json_encode($resp), 'status' => 200];

        $obj = (new Project($this->client, ['id' => 0]))->sections->create([
            'name' => ''
        ]);

        $this->assertSame($resp, $obj->data);
        $this->assertInstanceOf($ret_class, $obj);
    }


    public function testListProjectVersions() {
        $col_name = 'versions';
        $ret_class = 'LeanTesting\API\Client\Entity\Project\ProjectVersion';
        $resp = $this->rcol($col_name, ['_id', 'number', '_project_id']);
        $this->client->debug_return = ['data' => json_encode($resp), 'status' => 200];

        $col = (new Project($this->client, ['id' => 0]))->versions->all();

        $this->assertSame($resp[$col_name], $col->toArray());
        $this->assertInstanceOf($ret_class, $col->collection[0]);
        $this->assertEquals($resp['meta']['pagination']['total'], $col->total());
        $this->assertEquals($resp['meta']['pagination']['total_pages'], $col->totalPages());
        $this->assertEquals($resp['meta']['pagination']['count'], $col->count());
    }
    public function testAddProjectVersion() {
        $ret_class = 'LeanTesting\API\Client\Entity\Project\ProjectVersion';
        $resp = $this->robj(['_id', 'number', '_project_id']);
        $this->client->debug_return = ['data' => json_encode($resp), 'status' => 200];

        $obj = (new Project($this->client, ['id' => 0]))->versions->create([
            'number' => ''
        ]);

        $this->assertSame($resp, $obj->data);
        $this->assertInstanceOf($ret_class, $obj);
    }


    public function testListProjectUsers() {
        $col_name = 'users';
        $ret_class = 'LeanTesting\API\Client\Entity\Project\ProjectUser';
        $resp = $this->rcol($col_name, ['_id', 'username', 'first_name', 'last_name', 'avatar', 'email', 'created_at']);
        $this->client->debug_return = ['data' => json_encode($resp), 'status' => 200];

        $col = (new Project($this->client, ['id' => 0]))->users->all();

        $this->assertSame($resp[$col_name], $col->toArray());
        $this->assertInstanceOf($ret_class, $col->collection[0]);
        $this->assertEquals($resp['meta']['pagination']['total'], $col->total());
        $this->assertEquals($resp['meta']['pagination']['total_pages'], $col->totalPages());
        $this->assertEquals($resp['meta']['pagination']['count'], $col->count());
    }


    public function testListProjectBugTypeScheme() {
        $col_name = 'scheme';
        $ret_class = 'LeanTesting\API\Client\Entity\Project\ProjectBugScheme';
        $resp = $this->rcol($col_name, ['_id', 'name']);
        $this->client->debug_return = ['data' => json_encode($resp), 'status' => 200];

        $col = (new Project($this->client, ['id' => 0]))->bugTypeScheme->all();

        $this->assertSame($resp[$col_name], $col->toArray());
        $this->assertInstanceOf($ret_class, $col->collection[0]);
        $this->assertEquals($resp['meta']['pagination']['total'], $col->total());
        $this->assertEquals($resp['meta']['pagination']['total_pages'], $col->totalPages());
        $this->assertEquals($resp['meta']['pagination']['count'], $col->count());
    }
    public function testListProjectBugStatusScheme() {
        $col_name = 'scheme';
        $ret_class = 'LeanTesting\API\Client\Entity\Project\ProjectBugScheme';
        $resp = $this->rcol($col_name, ['_id', 'name']);
        $this->client->debug_return = ['data' => json_encode($resp), 'status' => 200];

        $col = (new Project($this->client, ['id' => 0]))->bugStatusScheme->all();

        $this->assertSame($resp[$col_name], $col->toArray());
        $this->assertInstanceOf($ret_class, $col->collection[0]);
        $this->assertEquals($resp['meta']['pagination']['total'], $col->total());
        $this->assertEquals($resp['meta']['pagination']['total_pages'], $col->totalPages());
        $this->assertEquals($resp['meta']['pagination']['count'], $col->count());
    }
    public function testListProjectBugSeverityScheme() {
        $col_name = 'scheme';
        $ret_class = 'LeanTesting\API\Client\Entity\Project\ProjectBugScheme';
        $resp = $this->rcol($col_name, ['_id', 'name']);
        $this->client->debug_return = ['data' => json_encode($resp), 'status' => 200];

        $col = (new Project($this->client, ['id' => 0]))->bugSeverityScheme->all();

        $this->assertSame($resp[$col_name], $col->toArray());
        $this->assertInstanceOf($ret_class, $col->collection[0]);
        $this->assertEquals($resp['meta']['pagination']['total'], $col->total());
        $this->assertEquals($resp['meta']['pagination']['total_pages'], $col->totalPages());
        $this->assertEquals($resp['meta']['pagination']['count'], $col->count());
    }
    public function testListProjectBugReproducibilityScheme() {
        $col_name = 'scheme';
        $ret_class = 'LeanTesting\API\Client\Entity\Project\ProjectBugScheme';
        $resp = $this->rcol($col_name, ['_id', 'name']);
        $this->client->debug_return = ['data' => json_encode($resp), 'status' => 200];

        $col = (new Project($this->client, ['id' => 0]))->bugReproducibilityScheme->all();

        $this->assertSame($resp[$col_name], $col->toArray());
        $this->assertInstanceOf($ret_class, $col->collection[0]);
        $this->assertEquals($resp['meta']['pagination']['total'], $col->total());
        $this->assertEquals($resp['meta']['pagination']['total_pages'], $col->totalPages());
        $this->assertEquals($resp['meta']['pagination']['count'], $col->count());
    }
    public function testListProjectBugPriorityScheme() {
        $col_name = 'scheme';
        $ret_class = 'LeanTesting\API\Client\Entity\Project\ProjectBugScheme';
        $resp = $this->rcol($col_name, ['_id', 'name']);
        $this->client->debug_return = ['data' => json_encode($resp), 'status' => 200];

        $col = (new Project($this->client, ['id' => 0]))->bugPriorityScheme->all();

        $this->assertSame($resp[$col_name], $col->toArray());
        $this->assertInstanceOf($ret_class, $col->collection[0]);
        $this->assertEquals($resp['meta']['pagination']['total'], $col->total());
        $this->assertEquals($resp['meta']['pagination']['total_pages'], $col->totalPages());
        $this->assertEquals($resp['meta']['pagination']['count'], $col->count());
    }
    /* END PROJECT */



    /* BUG */
    public function testListBugsInProject() {
        $col_name = 'bugs';
        $ret_class = 'LeanTesting\API\Client\Entity\Bug\Bug';
        $resp = $this->rcol($col_name, ['_id', 'title', '_status_id', '_severity_id', '_project_version_id',
            '_project_section_id', '_type_id', '_reproducibility_id', '_assigned_user_id', 'description',
            '_priority_id', 'expected_results']);
        $this->client->debug_return = ['data' => json_encode($resp), 'status' => 200];

        $col = (new Project($this->client, ['id' => 0]))->bugs->all();

        $this->assertSame($resp[$col_name], $col->toArray());
        $this->assertInstanceOf($ret_class, $col->collection[0]);
        $this->assertEquals($resp['meta']['pagination']['total'], $col->total());
        $this->assertEquals($resp['meta']['pagination']['total_pages'], $col->totalPages());
        $this->assertEquals($resp['meta']['pagination']['count'], $col->count());
    }
    public function testCreateNewBug() {
        $ret_class = 'LeanTesting\API\Client\Entity\Bug\Bug';
        $resp = $this->robj(['_id', 'title', '_status_id', '_severity_id', '_project_version_id',
            '_project_section_id', '_type_id', '_reproducibility_id', '_priority_id', '_assigned_user_id', 'description',
            'expected_results']);
        $this->client->debug_return = ['data' => json_encode($resp), 'status' => 200];

        $obj = (new Project($this->client, ['id' => 0]))->bugs->create([
            'title' => '', 'status_id' => 0, 'severity_id' => 0, 'project_version_id' => 0, 'project_section_id' => 0,
            'type_id' => 0, 'reproducibility_id' => 0, 'priority_id' => 0, 'assigned_user_id' => 0, 'description' => '',
            'expected_results' => ''
        ]);

        $this->assertSame($resp, $obj->data);
        $this->assertInstanceOf($ret_class, $obj);
    }
    public function testRetrieveExistingBug() {
        $ret_class = 'LeanTesting\API\Client\Entity\Bug\Bug';
        $resp = $this->robj(['_id', 'title', '_status_id', '_severity_id', '_project_version_id',
            '_project_section_id', '_type_id', '_reproducibility_id', '_priority_id', '_assigned_user_id', 'description',
            'expected_results']);
        $this->client->debug_return = ['data' => json_encode($resp), 'status' => 200];

        $obj = $this->client->bugs->find(0);

        $this->assertSame($resp, $obj->data);
        $this->assertInstanceOf($ret_class, $obj);
    }
    public function testUpdateBug() {
        $ret_class = 'LeanTesting\API\Client\Entity\Bug\Bug';
        $resp = $this->robj(['_id', 'title', '_status_id', '_severity_id', '_project_version_id',
            '_project_section_id', '_type_id', '_reproducibility_id', '_priority_id', '_assigned_user_id', 'description',
            'expected_results']);
        $this->client->debug_return = ['data' => json_encode($resp), 'status' => 200];

        $obj = $this->client->bugs->update(0, [
            'title' => '', 'status_id' => 0, 'severity_id' => 0, 'project_version_id' => 0, 'project_section_id' => 0,
            'type_id' => 0, 'assigned_user_id' => 0, 'description' => '', 'expected_results' => '', 'priority_id' => 0
        ]);

        $this->assertSame($resp, $obj->data);
        $this->assertInstanceOf($ret_class, $obj);
    }
    public function testDeleteBug() {
        $this->client->debug_return = ['data' => null, 'status' => 204];

        $data = $this->client->bugs->delete(0);

        $this->assertTrue($data);
    }
    /* END BUG */



    /* BUG COMMENTS */
    public function testListBugComments() {
        $col_name = 'comments';
        $ret_class = 'LeanTesting\API\Client\Entity\Bug\BugComment';
        $resp = $this->rcol($col_name, ['_id', 'text', '_owner_id', 'created_at']);
        $this->client->debug_return = ['data' => json_encode($resp), 'status' => 200];

        $col = (new Bug($this->client, ['id' => 0]))->comments->all();

        $this->assertSame($resp[$col_name], $col->toArray());
        $this->assertInstanceOf($ret_class, $col->collection[0]);
        $this->assertEquals($resp['meta']['pagination']['total'], $col->total());
        $this->assertEquals($resp['meta']['pagination']['total_pages'], $col->totalPages());
        $this->assertEquals($resp['meta']['pagination']['count'], $col->count());
    }
    /* END BUG COMMENTS */




    /* BUG ATTACHMENTS */
    public function testListBugAttachments() {
        $col_name = 'attachments';
        $ret_class = 'LeanTesting\API\Client\Entity\Bug\BugAttachment';
        $resp = $this->rcol($col_name, ['_id', '_owner_id', 'url', 'created_at']);
        $this->client->debug_return = ['data' => json_encode($resp), 'status' => 200];

        $col = (new Bug($this->client, ['id' => 0]))->attachments->all();

        $this->assertSame($resp[$col_name], $col->toArray());
        $this->assertInstanceOf($ret_class, $col->collection[0]);
        $this->assertEquals($resp['meta']['pagination']['total'], $col->total());
        $this->assertEquals($resp['meta']['pagination']['total_pages'], $col->totalPages());
        $this->assertEquals($resp['meta']['pagination']['count'], $col->count());
    }
    public function testCreateNewAttachment() {
        $ret_class = 'LeanTesting\API\Client\Entity\Bug\BugAttachment';
        $resp = $this->robj(['_id', '_owner_id', 'url', 'created_at']);
        $this->client->debug_return = ['data' => json_encode($resp), 'status' => 200];

        $_fp = __DIR__ . '/res/upload_sample.jpg';
        $obj = (new Bug($this->client, ['id' => 0]))->attachments->upload($_fp);

        $this->assertSame($resp, $obj->data);
        $this->assertInstanceOf($ret_class, $obj);
    }
    public function testRetrieveExistingAttachment() {
        $ret_class = 'LeanTesting\API\Client\Entity\Bug\BugAttachment';
        $resp = $this->robj(['_id', '_owner_id', 'url', 'created_at']);
        $this->client->debug_return = ['data' => json_encode($resp), 'status' => 200];

        $obj = $this->client->attachments->find(0);

        $this->assertSame($resp, $obj->data);
        $this->assertInstanceOf($ret_class, $obj);
    }
    public function testDeleteAttachment() {
        $this->client->debug_return = ['data' => null, 'status' => 204];

        $data = $this->client->attachments->delete(0);

        $this->assertTrue($data);
    }
    /* END BUG ATTACHMENTS */





    /* PLATFORM */
    public function testListPlatformTypes() {
        $col_name = 'types';
        $ret_class = 'LeanTesting\API\Client\Entity\Platform\PlatformType';
        $resp = $this->rcol($col_name, ['_id', 'name']);
        $this->client->debug_return = ['data' => json_encode($resp), 'status' => 200];

        $col = $this->client->platform->types->all();

        $this->assertSame($resp[$col_name], $col->toArray());
        $this->assertInstanceOf($ret_class, $col->collection[0]);
        $this->assertEquals($resp['meta']['pagination']['total'], $col->total());
        $this->assertEquals($resp['meta']['pagination']['total_pages'], $col->totalPages());
        $this->assertEquals($resp['meta']['pagination']['count'], $col->count());
    }
    public function testRetrievePlatformType() {
        $ret_class = 'LeanTesting\API\Client\Entity\Platform\PlatformType';
        $resp = $this->robj(['_id', 'name']);
        $this->client->debug_return = ['data' => json_encode($resp), 'status' => 200];

        $obj = $this->client->platform->types->find(0);

        $this->assertSame($resp, $obj->data);
        $this->assertInstanceOf($ret_class, $obj);
    }

    public function testListPlatformDevices() {
        $col_name = 'devices';
        $ret_class = 'LeanTesting\API\Client\Entity\Platform\PlatformDevice';
        $resp = $this->rcol($col_name, ['_id', 'name']);
        $this->client->debug_return = ['data' => json_encode($resp), 'status' => 200];

        $col = (new PlatformType($this->client, ['id' => 0]))->devices->all();

        $this->assertSame($resp[$col_name], $col->toArray());
        $this->assertInstanceOf($ret_class, $col->collection[0]);
        $this->assertEquals($resp['meta']['pagination']['total'], $col->total());
        $this->assertEquals($resp['meta']['pagination']['total_pages'], $col->totalPages());
        $this->assertEquals($resp['meta']['pagination']['count'], $col->count());
    }
    public function testRetrievePlatformDevice() {
        $ret_class = 'LeanTesting\API\Client\Entity\Platform\PlatformDevice';
        $resp = $this->robj(['_id', 'name']);
        $this->client->debug_return = ['data' => json_encode($resp), 'status' => 200];

        $obj = $this->client->platform->devices->find(0);

        $this->assertSame($resp, $obj->data);
        $this->assertInstanceOf($ret_class, $obj);
    }

    public function testListOS() {
        $col_name = 'os';
        $ret_class = 'LeanTesting\API\Client\Entity\Platform\PlatformOS';
        $resp = $this->rcol($col_name, ['_id', 'name']);
        $this->client->debug_return = ['data' => json_encode($resp), 'status' => 200];

        $col = $this->client->platform->os->all();

        $this->assertSame($resp[$col_name], $col->toArray());
        $this->assertInstanceOf($ret_class, $col->collection[0]);
        $this->assertEquals($resp['meta']['pagination']['total'], $col->total());
        $this->assertEquals($resp['meta']['pagination']['total_pages'], $col->totalPages());
        $this->assertEquals($resp['meta']['pagination']['count'], $col->count());
    }
    public function testRetrieveOS() {
        $ret_class = 'LeanTesting\API\Client\Entity\Platform\PlatformOS';
        $resp = $this->robj(['_id', 'name']);
        $this->client->debug_return = ['data' => json_encode($resp), 'status' => 200];

        $obj = $this->client->platform->os->find(0);

        $this->assertSame($resp, $obj->data);
        $this->assertInstanceOf($ret_class, $obj);
    }
    public function testListOSVersions() {
        $col_name = 'versions';
        $ret_class = 'LeanTesting\API\Client\Entity\Platform\PlatformOSVersion';
        $resp = $this->rcol($col_name, ['_id', 'number']);
        $this->client->debug_return = ['data' => json_encode($resp), 'status' => 200];

        $col = (new PlatformOS($this->client, ['id' => 0]))->versions->all();

        $this->assertSame($resp[$col_name], $col->toArray());
        $this->assertInstanceOf($ret_class, $col->collection[0]);
        $this->assertEquals($resp['meta']['pagination']['total'], $col->total());
        $this->assertEquals($resp['meta']['pagination']['total_pages'], $col->totalPages());
        $this->assertEquals($resp['meta']['pagination']['count'], $col->count());
    }

    public function testListBrowsers() {
        $col_name = 'browsers';
        $ret_class = 'LeanTesting\API\Client\Entity\Platform\PlatformBrowser';
        $resp = $this->rcol($col_name, ['_id', 'name']);
        $this->client->debug_return = ['data' => json_encode($resp), 'status' => 200];

        $col = $this->client->platform->browsers->all();

        $this->assertSame($resp[$col_name], $col->toArray());
        $this->assertInstanceOf($ret_class, $col->collection[0]);
        $this->assertEquals($resp['meta']['pagination']['total'], $col->total());
        $this->assertEquals($resp['meta']['pagination']['total_pages'], $col->totalPages());
        $this->assertEquals($resp['meta']['pagination']['count'], $col->count());
    }
    public function testRetrieveBrowser() {
        $ret_class = 'LeanTesting\API\Client\Entity\Platform\PlatformBrowser';
        $resp = $this->robj(['_id', 'name']);
        $this->client->debug_return = ['data' => json_encode($resp), 'status' => 200];

        $obj = $this->client->platform->browsers->find(0);

        $this->assertSame($resp, $obj->data);
        $this->assertInstanceOf($ret_class, $obj);
    }
    public function testListBrowserVersions() {
        $col_name = 'versions';
        $ret_class = 'LeanTesting\API\Client\Entity\Platform\PlatformBrowserVersion';
        $resp = $this->rcol($col_name, ['_id', 'name']);
        $this->client->debug_return = ['data' => json_encode($resp), 'status' => 200];

        $col = (new PlatformBrowser($this->client, ['id' => 0]))->versions->all();

        $this->assertSame($resp[$col_name], $col->toArray());
        $this->assertInstanceOf($ret_class, $col->collection[0]);
        $this->assertEquals($resp['meta']['pagination']['total'], $col->total());
        $this->assertEquals($resp['meta']['pagination']['total_pages'], $col->totalPages());
        $this->assertEquals($resp['meta']['pagination']['count'], $col->count());
    }
    /* END PLATFORM */

}
