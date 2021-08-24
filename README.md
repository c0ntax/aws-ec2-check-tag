# aws-ec2-check-tag

A very simple library that checks that, for the EC2 instance that this is run on, does a key === a certain value.

## Introduction

This was built to scratch a particular itch where I only wanted cron commands and other services to run on one of a set of EC2 instances.
This way you can deploy one AMI for a set of load balanced machines and simply tag one of them that you want to execute something on. For example, 
say we have 3 instances running the code base, but we only want a particular bit of code to run on one of them. Simply set a tag for that instance such as

```
RunHere: True
```

And then all you need to do so check that `RunHere` has been set to `True`

## Usage

Using the above example, you could use the following code:

```php

use C0ntax\Aws\Ec2\CheckTag\Exceptions\KeyNotFoundException;use C0ntax\Aws\Ec2\CheckTag\Exceptions\NotOnEc2InstanceException;public function doSomething()
{
  try {
    if (!$this->getCheckService()->check('RunHere', 'True')) {
      return;
    }
  } catch (NotOnEc2InstanceException $exception) {
    // This is probably running on a local instance so we might want to allow this to run
    // (i.e. we are just going to catch and ignore the exception)
  } catch (KeyNotFoundException $exception) {
    // The 'RunHere' key was not found. It's up to you how you've implimented it. It's probably not an error in most cases
    // It just means that you don't want to run anything here
    
    return;
  }
  
  print 'I am doing something';
}
```
