<?php

namespace LeanTesting\API\Client\Test;

use LeanTesting\API\Client\Client;
use LeanTesting\API\Client\Handler\Platform\PlatformHandler;
use LeanTesting\API\Client\Handler\User\UserHandler;

class HandlersTest extends \PHPUnit_Framework_TestCase
{

    private $handler_colllection = [
        ['LeanTesting\API\Client\Handler\Attachment\AttachmentsHandler'],
        ['LeanTesting\API\Client\Handler\Bug\BugAttachmentsHandler',                        'requiresIDInConstructor'],
        ['LeanTesting\API\Client\Handler\Bug\BugCommentsHandler',                           'requiresIDInConstructor'],
        ['LeanTesting\API\Client\Handler\Bug\BugsHandler'],
        ['LeanTesting\API\Client\Handler\Platform\PlatformBrowsersHandler'],
        ['LeanTesting\API\Client\Handler\Platform\PlatformBrowserVersionsHandler',          'requiresIDInConstructor'],
        ['LeanTesting\API\Client\Handler\Platform\PlatformDevicesHandler'],
        ['LeanTesting\API\Client\Handler\Platform\PlatformHandler'],
        ['LeanTesting\API\Client\Handler\Platform\PlatformOSHandler'],
        ['LeanTesting\API\Client\Handler\Platform\PlatformOSVersionsHandler',               'requiresIDInConstructor'],
        ['LeanTesting\API\Client\Handler\Platform\PlatformTypeDevicesHandler',              'requiresIDInConstructor'],
        ['LeanTesting\API\Client\Handler\Platform\PlatformTypesHandler'],
        ['LeanTesting\API\Client\Handler\Project\ProjectBugReproducibilitySchemeHandler',   'requiresIDInConstructor'],
        ['LeanTesting\API\Client\Handler\Project\ProjectBugSeveritySchemeHandler',          'requiresIDInConstructor'],
        ['LeanTesting\API\Client\Handler\Project\ProjectBugsHandler',                       'requiresIDInConstructor'],
        ['LeanTesting\API\Client\Handler\Project\ProjectBugStatusSchemeHandler',            'requiresIDInConstructor'],
        ['LeanTesting\API\Client\Handler\Project\ProjectBugTypeSchemeHandler',              'requiresIDInConstructor'],
        ['LeanTesting\API\Client\Handler\Project\ProjectSectionsHandler',                   'requiresIDInConstructor'],
        ['LeanTesting\API\Client\Handler\Project\ProjectsHandler'],
        ['LeanTesting\API\Client\Handler\Project\ProjectUsersHandler',                      'requiresIDInConstructor'],
        ['LeanTesting\API\Client\Handler\Project\ProjectVersionsHandler',                   'requiresIDInConstructor'],
        ['LeanTesting\API\Client\Handler\User\UserHandler'],
        ['LeanTesting\API\Client\Handler\User\UserOrganizationsHandler']
    ];

    public function testHandlersDefined() {
        foreach ($this->handler_colllection as $e) {
            $this->assertTrue(class_exists($e[0]));
        }
    }






    public function testHandlersCreateNonArrFields() {
        $expects_exception = 'LeanTesting\API\Client\Exception\SDKInvalidArgException';
        foreach ($this->handler_colllection as $e) {
            try {
                if (array_key_exists(1, $e) && $e[1] === 'requiresIDInConstructor') {
                    (new $e[0](new Client, 999))->create('');
                } else {
                    (new $e[0](new Client))->create('');
                }

                $this->fail('No exception thrown. Expected ' . $expects_exception);
            } catch(\Exception $ex) {
                if (get_class($ex) !== $expects_exception) {
                    $this->fail('Unexpected exception received. Expected ' . $expects_exception);
                }
            }
        }
    }
    public function testHandlersCreateEmptyFields() {
        $expects_exception = 'LeanTesting\API\Client\Exception\SDKInvalidArgException';
        foreach ($this->handler_colllection as $e) {
            try {
                if (array_key_exists(1, $e) && $e[1] === 'requiresIDInConstructor') {
                    (new $e[0](new Client, 999))->create([]);
                } else {
                    (new $e[0](new Client))->create([]);
                }

                $this->fail('No exception thrown. Expected ' . $expects_exception);
            } catch(\Exception $ex) {
                if (get_class($ex) !== $expects_exception) {
                    $this->fail('Unexpected exception received. Expected ' . $expects_exception);
                }
            }
        }
    }



    public function testHandlersAllNonArrFilters() {
        $expects_exception = 'LeanTesting\API\Client\Exception\SDKInvalidArgException';
        foreach ($this->handler_colllection as $e) {
            try {
                if (array_key_exists(1, $e) && $e[1] === 'requiresIDInConstructor') {
                    (new $e[0](new Client, 999))->all('');
                } else {
                    (new $e[0](new Client))->all('');
                }

                $this->fail('No exception thrown. Expected ' . $expects_exception);
            } catch(\Exception $ex) {
                if (get_class($ex) !== $expects_exception) {
                    $this->fail('Unexpected exception received. Expected ' . $expects_exception);
                }
            }
        }
    }
    public function testHandlersAllInvalidFilters() {
        $expects_exception = 'LeanTesting\API\Client\Exception\SDKInvalidArgException';
        foreach ($this->handler_colllection as $e) {
            try {
                if (array_key_exists(1, $e) && $e[1] === 'requiresIDInConstructor') {
                    (new $e[0](new Client, 999))->all(['XXXfilterXXX' => 9999]);
                } else {
                    (new $e[0](new Client))->all(['XXXfilterXXX' => 9999]);
                }

                $this->fail('No exception thrown. Expected ' . $expects_exception);
            } catch(\Exception $ex) {
                if (get_class($ex) !== $expects_exception) {
                    $this->fail('Unexpected exception received. Expected ' . $expects_exception);
                }
            }
        }
    }
    public function testHandlersAllSupportedFilters() {
        $client = new Client;
        $client->debug_return = [
            'data' => '{"x": [], "meta": {"pagination": {}}}',
            'status' => 200
        ];

        foreach ($this->handler_colllection as $e) {
            if (array_key_exists(1, $e) && $e[1] === 'requiresIDInConstructor') {
                $inst = new $e[0]($client, 999);
            } else {
                $inst = new $e[0]($client);
            }

            $inst->all(['include' => '']);
            $inst->all(['limit' => 10]);
            $inst->all(['page' => 2]);
        }
    }



    public function testHandlersFindNonIntID() {
        $expects_exception = 'LeanTesting\API\Client\Exception\SDKInvalidArgException';
        foreach ($this->handler_colllection as $e) {
            try {
                if (array_key_exists(1, $e) && $e[1] === 'requiresIDInConstructor') {
                    (new $e[0](new Client, 999))->find('');
                } else {
                    (new $e[0](new Client))->find('');
                }

                $this->fail('No exception thrown. Expected ' . $expects_exception);
            } catch(\Exception $ex) {
                if (get_class($ex) !== $expects_exception) {
                    $this->fail('Unexpected exception received. Expected ' . $expects_exception);
                }
            }
        }
    }



    public function testHandlersDeleteNonIntID() {
        $expects_exception = 'LeanTesting\API\Client\Exception\SDKInvalidArgException';
        foreach ($this->handler_colllection as $e) {
            try {
                if (array_key_exists(1, $e) && $e[1] === 'requiresIDInConstructor') {
                    (new $e[0](new Client, 999))->delete('');
                } else {
                    (new $e[0](new Client))->delete('');
                }

                $this->fail('No exception thrown. Expected ' . $expects_exception);
            } catch(\Exception $ex) {
                if (get_class($ex) !== $expects_exception) {
                    $this->fail('Unexpected exception received. Expected ' . $expects_exception);
                }
            }
        }
    }



    public function testHandlersUpdateNonIntID() {
        $expects_exception = 'LeanTesting\API\Client\Exception\SDKInvalidArgException';
        foreach ($this->handler_colllection as $e) {
            try {
                if (array_key_exists(1, $e) && $e[1] === 'requiresIDInConstructor') {
                    (new $e[0](new Client, 999))->update('', ['x' => 5]);
                } else {
                    (new $e[0](new Client))->update('', ['x' => 5]);
                }

                $this->fail('No exception thrown. Expected ' . $expects_exception);
            } catch(\Exception $ex) {
                if (get_class($ex) !== $expects_exception) {
                    $this->fail('Unexpected exception received. Expected ' . $expects_exception);
                }
            }
        }
    }
    public function testHandlersUpdateNonArrFields() {
        $expects_exception = 'LeanTesting\API\Client\Exception\SDKInvalidArgException';
        foreach ($this->handler_colllection as $e) {
            try {
                if (array_key_exists(1, $e) && $e[1] === 'requiresIDInConstructor') {
                    (new $e[0](new Client, 999))->update(5, '');
                } else {
                    (new $e[0](new Client))->update(5, '');
                }

                $this->fail('No exception thrown. Expected ' . $expects_exception);
            } catch(\Exception $ex) {
                if (get_class($ex) !== $expects_exception) {
                    $this->fail('Unexpected exception received. Expected ' . $expects_exception);
                }
            }
        }
    }
    public function testHandlersUpdateEmptyFields() {
        $expects_exception = 'LeanTesting\API\Client\Exception\SDKInvalidArgException';
        foreach ($this->handler_colllection as $e) {
            try {
                if (array_key_exists(1, $e) && $e[1] === 'requiresIDInConstructor') {
                    (new $e[0](new Client, 999))->update(5, []);
                } else {
                    (new $e[0](new Client))->update(5, []);
                }

                $this->fail('No exception thrown. Expected ' . $expects_exception);
            } catch(\Exception $ex) {
                if (get_class($ex) !== $expects_exception) {
                    $this->fail('Unexpected exception received. Expected ' . $expects_exception);
                }
            }
        }
    }




    /* HAVE SECONDARIES */
    public function testPlatformHandlerHasTypes() {
        $this->assertInstanceOf('LeanTesting\API\Client\Handler\Platform\PlatformTypesHandler', (new PlatformHandler(new Client))->types);
    }
    public function testPlatformHandlerHasDevices() {
        $this->assertInstanceOf('LeanTesting\API\Client\Handler\Platform\PlatformDevicesHandler', (new PlatformHandler(new Client))->devices);
    }
    public function testPlatformHandlerHasOS() {
        $this->assertInstanceOf('LeanTesting\API\Client\Handler\Platform\PlatformOSHandler', (new PlatformHandler(new Client))->os);
    }
    public function testPlatformHandlerHasBrowsers() {
        $this->assertInstanceOf('LeanTesting\API\Client\Handler\Platform\PlatformBrowsersHandler', (new PlatformHandler(new Client))->browsers);
    }
    public function testUserHandlerHasOrganizations() {
        $this->assertInstanceOf('LeanTesting\API\Client\Handler\User\UserOrganizationsHandler', (new UserHandler(new Client))->organizations);
    }
    /* END HAVE SECONDARIES */

}
