## API version support

This client **only** supports Zendesk's API v2.  Please see our [API documentation](http://developer.zendesk.com) for more information.

## Requirements

* PHP 7.4+

## Installation

The Zendesk PHP API client can be installed using [Composer](https://packagist.org/packages/qurbanali/zendesk-php).

### Composer

To install run `composer require qurbanali/zendesk-php`

## Configuration

Configuration is done through an instance of `Qurban\ZendeskAPI\HttpClient`.
The block is mandatory and if not passed, an error will be thrown.

``` php
// load Composer
require 'vendor/autoload.php';

use Qurban\ZendeskAPI\HttpClient as ZendeskAPI;

$subdomain = "subdomain";
$username  = "email@example.com"; // replace this with your registered email
$token     = "6wiIBWbGkBMo1mRDMuVwkw1EPsNkeUj95PIz2akv"; // replace this with your token

$client = new ZendeskAPI($subdomain);
$client->setAuth('basic', ['username' => $username, 'token' => $token]);
```

## Usage

### Basic Operations

``` php
// Get all tickets
$tickets = $client->tickets()->findAll();
echo $tickets;

// Get all tickets regarding a specific user.
$tickets = $client->users($requesterId)->tickets()->requested();
echo $tickets;

// Create a new ticket
$newTicket = $client->tickets()->create([
    'subject'  => 'The quick brown fox jumps over the lazy dog',
    'comment'  => [
        'body' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, ' .
                  'sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'
    ],
    'priority' => 'normal'
]);
echo $newTicket;

// Update a ticket
$client->tickets()->update(123,[
    'priority' => 'high'
]);

// Delete a ticket
$client->tickets()->delete(123);

// Get all users
$users = $client->users()->findAll();
echo $users;
```

### Attachments

``` php
$attachment = $client->attachments()->upload([
    'file' => getcwd().'/tests/assets/UK.png',
    'type' => 'image/png',
    'name' => 'UK.png' // Optional parameter, will default to filename.ext
]);
```

Attaching files to comments

``` php
$ticket = $client->tickets()->create([
    'subject' => 'The quick brown fox jumps over the lazy dog',
    'comment' => [
        'body' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, ' .
                  'sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
        'uploads'   => [$attachment->upload->token]
    ]
]);
```

### Side-loading

Side-loading allows you to retrieve related records as part of a single request. See [the documentation] for more information. (https://developer.zendesk.com/rest_api/docs/core/side_loading).

An example of sideloading with the client is shown below.

``` php
$tickets = $client->tickets()->sideload(['users', 'groups'])->findAll();
```

### Pagination

Methods like `findAll()` call the API without any pagination parameter. If an endpoint supports pagination, only the first page will be returned. To fetch all resources, you need to make multiple API calls.

#### Iterator (recommended)

The use of the correct type of pagination is encapsulated using an iterator, which allows you to retrieve all resources in all pages, making multiple API calls, without having to worry about pagination at all:

```php
$iterator = $client->tickets()->iterator();

foreach ($iterator as $ticket) {
    echo($ticket->id . " ");
}
```

If you want a specific sort order, please refer to the sorting section in the documentation ([Tickets, for example](https://developer.zendesk.com/api-reference/ticketing/tickets/tickets/#sorting)).

##### Iterator with params example

```php
$params = ['my' => 'param1', 'extra' => 'param2'];
$iterator = $client->tickets()->iterator($params);

foreach ($iterator as $ticket) {
    echo($ticket->id . " ");
}
```

* Change page size with: `$params = ['page[size]' => 5];`
* Change sorting with: `$params = ['sort' => '-updated_at'];`
  * Refer to the docs for details, including allowed sort fields
* Combine everything: `$params = ['page[size]' => 2, 'sort' => 'updated_at', 'extra' => 'param'];`

**Note**:

* Refer to the documentation for the correct params for sorting with the pagination type you're using
* The helper method `iterator_to_array` doesn't work with this implementation

##### Iterator API call response

The latest response is exposed in the iterator at `$iterator->latestResponse()`. This could come handy for debugging.

##### Custom iterators

If you want to use the iterator for custom methods, as opposed to the default `findAll()`, you can create an iterator for your collection:

```php
$strategy = new CbpStrategy( // Or ObpStrategy or SinglePageStrategy
    "resources_key", // The root key with resources in the response, usually plural and in underscore
    [], // Extra params for your call
);
$iterator = PaginationIterator($client->tickets(), $strategy);
foreach ($ticketsIterator as $ticket) {
    // Use as normal
}
```

This can be useful for filter endpoints like [active automations](https://developer.zendesk.com/api-reference/ticketing/business-rules/automations/#list-active-automations). However, in this common case where you only need to change the method from `findAll()` to `findActive()` there's a better shortcut:

```php
$iterator = $client->automations()->iterator($params, 'findActive');
```

Which is analogous to:

```php
use Qurban\ZendeskAPI\Traits\Utility\Pagination\PaginationIterator;
use Qurban\ZendeskAPI\Traits\Utility\Pagination\CbpStrategy;
$strategy = new CbpStrategy('automations', $params);
$iterator = new PaginationIterator(
    $client->automations(),
    $strategy,
    'findActive'
);
```

See how the [Pagination Trait](src/Zendesk/API/Traits/Resource/Pagination.php) is used if you need more custom implementations.

##### Catching API errors

This doesn't change too much:

```php
try {
    foreach ($iterator as $ticket) {
        // your code
    }
} catch (ApiResponseException $e) {
    $errorMessage = $e->getMessage();
    $errorDetails = $e=>getErrorDetails();
}
```

If you need to know at what point you got the error, you can store the required information inside the loop in your code.

#### FindAll using CBP (fine)

If you still want use `findAll()`, until CBP becomes the default API response, you must explicitly request CBP responses by using the param `page[size]`.

``` php
// CBP: /path?page[size]=100
$response = $client->tickets()->findAll(['page[size]' => 100]);
process($response->tickets); // Your implementation
do {
    if ($response->meta->has_more) {
        // CBP: /path?page[after]=cursor
        $response = $client->tickets()->findAll(['page[after]' => $response->meta->after_cursor]);
        process($response->tickets);
    }
} while ($response->meta->has_more);
```

**Process data _immediately_ upon fetching**. This optimizes memory usage, enables real-time processing, and helps adhere to API rate limits, enhancing efficiency and user experience.

#### Find All using OBP (only recommended if the endpoint doesn't support CBP)

If CBP is not available, this is how you can fetch one page at a time:

```php
$pageSize = 100;
$pageNumber = 1;
do {
    // OBP: /path?per_page=100&page=2
    $response = $client->tickets()->findAll(['per_page' => $pageSize, 'page' => $pageNumber]);
    process($response->tickets); // Your implementation
    $pageNumber++;
} while (count($response->tickets) == $pageSize);
```

**Process data _immediately_ upon fetching**. This optimizes memory usage, enables real-time processing, and helps adhere to API rate limits, enhancing efficiency and user experience.

### Retrying Requests

Add the `RetryHandler` middleware on the `HandlerStack` of your `GuzzleHttp\Client` instance. By default `Qurban\ZendeskAPI\HttpClient`
retries:

* timeout requests
* those that throw `Psr\Http\Message\RequestInterface\ConnectException:class`
* and those that throw `Psr\Http\Message\RequestInterface\RequestException:class` that are identified as ssl issue.

#### Available options

Options are passed on `RetryHandler` as an array of values.

* max = 2 _limit of retries_
* interval = 300 _base delay between retries in milliseconds_
* max_interval = 20000 _maximum delay value_
* backoff_factor = 1 _backoff factor_
* exceptions = [ConnectException::class] _Exceptions to retry without checking retry_if_
* retry_if = null _callable function that can decide whether to retry the request or not_

## Contributing

Pull Requests are always welcome but before you send one please read our [contribution guidelines](#CONTRIBUTING.md). It would
speed up the process and would make sure that everybody follows the community's standard.

#### HTTP client print API calls

You can print a line with details about every API call with:

```php
$client = new ZendeskAPI($subdomain);
$client->log_api_calls = true;
```
