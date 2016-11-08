<?php
namespace LeanTesting\API\Client\Test;

use LeanTesting\API\Client\Client;
use LeanTesting\API\Client\Exception\SDKInvalidArgException;

class EntitiesTest extends \PHPUnit_Framework_TestCase
{

    private $entity_collection = [
        [
            'LeanTesting\API\Client\Entity\Bug\Bug',
            [
                'comments'    => 'LeanTesting\API\Client\Handler\Bug\BugCommentsHandler',
                'attachments' => 'LeanTesting\API\Client\Handler\Bug\BugAttachmentsHandler',
            ],
        ],
        ['LeanTesting\API\Client\Entity\Bug\BugAttachment'],
        ['LeanTesting\API\Client\Entity\Bug\BugComment'],
        [
            'LeanTesting\API\Client\Entity\Platform\PlatformBrowser',
            [
                'versions' => 'LeanTesting\API\Client\Handler\Platform\PlatformBrowserVersionsHandler',
            ],
        ],
        ['LeanTesting\API\Client\Entity\Platform\PlatformBrowserVersion'],
        ['LeanTesting\API\Client\Entity\Platform\PlatformDevice'],
        [
            'LeanTesting\API\Client\Entity\Platform\PlatformOS',
            [
                'versions' => 'LeanTesting\API\Client\Handler\Platform\PlatformOSVersionsHandler',
            ],
        ],
        ['LeanTesting\API\Client\Entity\Platform\PlatformOSVersion'],
        [
            'LeanTesting\API\Client\Entity\Platform\PlatformType',
            [
                'devices' => 'LeanTesting\API\Client\Handler\Platform\PlatformTypeDevicesHandler',
            ],
        ],
        [
            'LeanTesting\API\Client\Entity\Project\Project',
            [
                'sections' => 'LeanTesting\API\Client\Handler\Project\ProjectSectionsHandler',
                'versions' => 'LeanTesting\API\Client\Handler\Project\ProjectVersionsHandler',
                'users'    => 'LeanTesting\API\Client\Handler\Project\ProjectUsersHandler',

                'bugTypeScheme'            => 'LeanTesting\API\Client\Handler\Project\ProjectBugTypeSchemeHandler',
                'bugStatusScheme'          => 'LeanTesting\API\Client\Handler\Project\ProjectBugStatusSchemeHandler',
                'bugSeverityScheme'        => 'LeanTesting\API\Client\Handler\Project\ProjectBugSeveritySchemeHandler',
                'bugReproducibilityScheme' => 'LeanTesting\API\Client\Handler\Project\ProjectBugReproducibilitySchemeHandler',
                'bugPriorityScheme'        => 'LeanTesting\API\Client\Handler\Project\ProjectBugPrioritySchemeHandler',

                'bugs' => 'LeanTesting\API\Client\Handler\Project\ProjectBugsHandler',
            ],
        ],
        ['LeanTesting\API\Client\Entity\Project\ProjectBugScheme'],
        ['LeanTesting\API\Client\Entity\Project\ProjectSection'],
        ['LeanTesting\API\Client\Entity\Project\ProjectUser'],
        ['LeanTesting\API\Client\Entity\Project\ProjectVersion'],
        ['LeanTesting\API\Client\Entity\Project\ProjectTestCase'],
        ['LeanTesting\API\Client\Entity\Project\ProjectTestRun'],
        ['LeanTesting\API\Client\Entity\Project\ProjectTestResult'],
        ['LeanTesting\API\Client\Entity\User\UserOrganization'],
    ];

    public function testEntitiesDefined() {
        foreach ($this->entity_collection as $e) {
            $this->assertTrue(class_exists($e[0]));
        }
    }

    public function testEntitiesCorrectParent() {
        foreach ($this->entity_collection as $e) {
            $this->assertInstanceOf('LeanTesting\API\Client\BaseClass\Entity', new $e[0](new Client, ['id' => 1]));
        }
    }

    public function testEntitiesDataParsing() {
        $data = ['id' => 1, 'YY' => 'strstr', 'FF' => [1, 2, 3, 'asdasdasd'], 'GG' => ['test1' => true, 'test2' => []]];
        foreach ($this->entity_collection as $e) {
            $this->assertSame((new $e[0](new Client, $data))->data, $data);
        }
    }

    public function testEntitiesInstanceNonArrData() {
        foreach ($this->entity_collection as $e) {
            try {
                new $e[0](new Client, '');
                $this->fail('No exception thrown');
            } catch(SDKInvalidArgException $ex) {
            } catch(\Exception $ex) {
                $this->fail('Unexpected exception received');
            }
        }
    }

    public function testEntitiesInstanceEmptyData() {
        foreach ($this->entity_collection as $e) {
            try {
                new $e[0](new Client, []);
                $this->fail('No exception thrown');
            } catch(SDKInvalidArgException $ex) {
            } catch(\Exception $ex) {
                $this->fail('Unexpected exception received');
            }
        }
    }

    public function testEntitiesHaveSecondaries() {
        foreach ($this->entity_collection as $e) {
            if (!array_key_exists(1, $e)) {
                continue;
            }

            foreach ($e[1] as $sk => $sv) {
                $this->assertInstanceOf($sv, (new $e[0](new Client, ['id' => 1]))->$sk);
            }
        }
    }

}
