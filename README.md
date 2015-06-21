# data_decorator_classThis is a decorator class used to add functionality to an existing class, without changing or sub-classing it. It provides object orientated access to serialised data transparently.

The intended object structure is hydrated from a serialised string (which was produced from the original array structure) into a collection of properties. Each of these properties acts as a standard getter/setter. Properties can be iterative, meaning that a property can hold another collection of properties (to any nesting level). These properties can be accessed, updated and deleted, like any other property. The final object structure can then be dehydrated back into a serialised string. This allows you to store any type of array structure, and still be able to access it in a standard object orientated way.

See here for a full description of a decorator pattern (https://en.wikipedia.org/?title=Decorator_pattern).


Example usage:

//$serialised_string could typically be retrieved from a database
									
$consumer = new App\versionDataDecorator($serialised_string);

//simple get/set for single data item
echo $consumer->get('tertiary_content');
$consumer->set('tertiary_content', 'new data goes here');

//simple get/set for single date array
print_r($consumer->get('primary_content'));

//2nd level iterative get/set
echo $consumer->get('secondary_content')->get('account_id');
$consumer->get('secondary_content')->set('account_id', 'new account text');

//3rd level iterative get/set
echo $consumer->get('secondary_content')->get('branch')->get('name');
$consumer->get('secondary_content')->get('branch')->set('code', 'your new code');

//dehydrate updated data 
$serialised_string = $consumer->dehydrate();

//$serialised_string could then by saved back to a database

