
# Lean Testing PHP SDK

[![Latest Stable Version](https://poser.pugx.org/cst/leantesting/v/stable)](https://packagist.org/packages/cst/leantesting)
[![License](https://poser.pugx.org/cst/leantesting/license)](https://packagist.org/packages/cst/leantesting)

**PHP client for [Lean Testing](https://leantesting.com/) API**

You can sign up for a Lean Testing account at [https://leantesting.com](https://leantesting.com).

## Requirements

* PHP 5.4 or greater

## Installation

The library is installed via [Composer](http://getcomposer.org/). To install, simply add it
to your `composer.json` file:

```json
{
    "require": {
        "cst/leantesting": "~1.0"
    }
}
```

And run composer to update your dependencies:

    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar update

## Basic Usage

- Instantiate the client
```php
$client = new LeanTesting\API\Client\Client();
$client->attachToken('<your token>');

// Listing projects
$projects = $client->projects->all();

// Fetching project bugs
$bugs = $client->projects->find(123)->bugs->all();
```

## Methods

- Get Current **Token**
```php
$client->getCurrentToken()
```

- Attach New **Token**
```php
$client->attachToken('<token>');
```

- Generate **Authorization URL**
```php
$generated_URL = $client->auth->generateAuthLink(
	'DHxaSvtpl91Xos4vb7d0GKkXRu0GJxd5Rdha2HHx', // client id
  'https://www.example.com/appurl/',
	'write', // scope
	'a3ahdh2iqhdasdasfdjahf26' // random string
);
print_r( $generated_URL );
```

- Exchange authorization code for an **access token**
```php
$token = $client->auth->exchangeAuthCode(
	'DHxaSvtpl91Xos4vb7d0GKkXRu0GJxd5Rdha2asdasdx', // client id
	'DpOZxNbeL1arVbjUINoA9pOhgS8FNQsOkpE4CtXU', // client secret
	'authorization_code',
	'JKHanMA897A7KA9ajqmxly', // auth code
	'https://www.example.com/appurl/'
);
print_r( $token );
```

----

- Get **User** Information
```php
$client->user->getInformation()
```

- Get **User** Organizations
```php
$client->user->organizations->all()->toArray()
```

- Retrieve An Existing **User** Organization
```php
$client->user->organizations->find(31)->data
```
----

- List All **Projects**
```php
$client->projects->all()->toArray()
```

- Create A New **Project**
```php
$new_project = $client->projects->create([
	'name' => 'Project135',
	'organization_id' => 5779
]);
print_r( $new_project->data );
```

- Retrieve An Existing **Project**
```php
$client->projects->find(3515)->data
```


- List **Project Sections**
```php
$client->projects->find(3515)->sections->all()->toArray()
```

- Adding A **Project Section**
```php
$new_section = $client->projects->find(3515)->sections->create([
	'name' => 'SectionName'
]);
print_r( $new_section->data );
```


- List **Project Versions**
```php
$client->projects->find(3515)->versions->all()->toArray()
```

- Adding A **Project Version**
```php
$new_version = $client->projects->find(3515)->versions->create([
	'number' => 'v0.3.1104'
]);
print_r( $new_version->data );
```

- List **Project Test cases**

```php
$client->projects->find(3515)->testCases->all()->toArray();
```

- List **Project Test runs**

```php
$client->projects->find(3515)->testRuns->all()->toArray();
```

- Retrieve **Test run** results
```php
$client->projects->find(3515)->testRuns->find(123)->data;
```

- List **Project Users**
```php
$client->projects->find(3515)->users->all()->toArray();
```

- Remove **Project Users**
```php
$client->projects->find(3515)->users->delete(123);
```

- List **Bug Type Scheme**
```php
$client->projects->find(3515)->bugTypeScheme->all()->toArray()
```

- List **Bug Status Scheme**
```php
$client->projects->find(3515)->bugStatusScheme->all()->toArray()
```

- List **Bug Severity Scheme**
```php
$client->projects->find(3515)->bugSeverityScheme->all()->toArray()
```

- List **Bug Reproducibility Scheme**
```php
$client->projects->find(3515)->bugReproducibilityScheme->all()->toArray()
```

----

- List All **Bugs** In A Project
```php
$client->projects->find(3515)->bugs->all()->toArray()
```

- Create A New **Bug**
```php
$new_bug = $client->projects->find(3515)->bugs->create([
	'title' => 'Something bad happened...',
	'status_id' => 1,
	'severity_id' => 2,
	'project_version_id' => 123
]);
print_r( $new_bug->data );
```

- Retrieve Existing **Bug**
```php
$client->bugs->find(123)->data
```

- Update A **Bug**
```php
$updated_bug = $client->bugs->update(123, [
	'title' => 'Updated title',
	'status_id' => 1,
	'severity_id' => 2,
	'project_version_id' => 123
]);
print_r( $updated_bug->data );
```

- Delete A **Bug**
```php
$client->bugs->delete(123)
```

----

- List Bug **Comments**
```php
$client->bugs->find(123)->comments->all()->toArray()
```

----

- List Bug **Attachments**
```php
$client->bugs->find(123)->attachments->all()->toArray()
```

- Upload An **Attachment**
```php
$file_path = '/place/Downloads/Images/1370240743_2294218.jpg';
$new_attachment = $client->bugs->find(123)->attachments->upload($file_path);
print_r( $new_attachment->data )
```

- Retrieve An Existing **Attachment**
```php
$client->attachments->find(21515)->data
```

- Delete An **Attachment**
```php
$client->attachments->delete(75198)
```

----

- List **Platform Types**
```php
$client->platform->types->all()->toArray()
```

- Retrieve **Platform Type**
```php
$client->platform->types->find(1)->data
```


- List **Platform Devices**
```php
$client->platform->types->find(1)->devices->all()->toArray()
```

- Retrieve Existing **Device**
```php
$client->platform->devices->find(11)->data
```


- List **OS**
```php
$client->platform->os->all()->toArray()
```

- Retrieve Existing **OS**
```php
$client->platform->os->find(1)->data
```

- List **OS Versions**
```php
$client->platform->os->find(1)->versions->all()->toArray()
```


- List **Browsers**
```php
$client->platform->browsers->all()->toArray()
```

- Retrieve Existing **Browser**
```php
$client->platform->browsers->find(1)->data
```

- List **Browser Versions**
```php
$client->platform->browsers->find(1)->versions->all()->toArray()
```

----

- Using **Filters**
```php
$client->projects->find(3515)->bugs->all(['limit' => 2, 'page' => 5]).toArray();
```

- **Entity List** Functions
```php
$browsers = $client->platform->browsers->all()
echo $browsers->total()
echo $browsers->totalPages()
echo $browsers->count()
echo $browsers->toArray()
```

- **Entity List** Iterator
When used in foreach() loops, entity lists will automatically rewind, regardless of `page` filter.
After ending the loop, the entity list will **NOT** revert to first page or the initial instancing `page` filter setting in order not to cause useless API request calls.
```php
$comments = $client->bugs->find(123)->comments->all(['limit' => 1]);
foreach ($comments as $page) {
	print_r( $page );
}
```

- **Entity List** Manual Iteration
```php
$comments = $client->bugs->find(123)->comments->all(['limit' => 1]);
echo $comments->toArray();

// Will return false if unable to move forwards
$comments->next();      echo $comments->toArray();

// Will return false if already on last page
$comments->last();      echo $comments->toArray();

// Will return false if unable to move backwards
$comments->previous();  echo $comments->toArray();

// Will return false if already on first page
$comments->first();     echo $comments->toArray();
```


## Security

Need to report a security vulnerability? Send us an email to support@crowdsourcedtesting.com or go directly to our security bug bounty site [https://hackerone.com/leantesting](https://hackerone.com/leantesting).

## Development

Install dependencies:

``` bash
composer install
```

## Tests

Install dependencies as mentioned above (which will resolve [PHPUnit](http://packagist.org/packages/phpunit/phpunit)), then you can run the test suite:

```bash
./vendor/bin/phpunit
```

## Contributing

Please see [CONTRIBUTING](https://github.com/crowdsourcedtesting/leantesting-php/blob/master/CONTRIBUTING.md) for details.