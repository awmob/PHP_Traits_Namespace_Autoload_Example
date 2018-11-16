Namespaces are a great way to differentiate and organize your code, especially across larger projects. Namespaces can make implementation of file autoloaders a little bit tricky though. Hell, they can sometimes make for hours of hair-pulling frustration!

This article aims to explore the exciting world of autoloading in php, and how they relate to traits and namespaces. 

We’ll go through an example of autoload implementation with four php files:

	2 class files
	A trait file
	An index file. 

Here is our file structure

	public_html / index.php
	public_html  / Cat.php (our class file)
	public_html  / Fridge.php (our class file)
	public_html  / Includes / Helpers.php (our trait file)


Here is what our Helpers.php file looks like:

      <?php
        namespace Includes;

        trait Helpers{
          public function set_age($age_input){
            $this->age = $age_input;
          }

          public function get_age(){
            return $this->age;
          }
        }

Notice that we’ve set the namespace to ‘Includes’. This makes logical sense, because the file system location of this Helpers.php file is public_html/Includes. The implications of this will be clear when we set our class file. 

Our Cat.php class file  looks like this:

      <?php

        class Cat{
          use Includes\Helpers;
          private $age;

        }

Our Fridge.php class file  looks like this:

      <?php

        class Fridge{
          use Includes\Helpers;
          private $age;

        }


Well, isn’t that short and sweet? What we’re doing here is allowing our class file to implement the Helpers traits file. 

What does that mean? What are traits and why are they useful? In short, it’s all about objects, lack of multiple inheritance in PHP (ooh, jargon), and sharing of resources. Classes are supposed to relate to a single type of task or quality. For example, if I want to express a cat, I create a Cat class. Cats can meow, so to express this action I could create a meow() function within my Cat class. 

But what if I want to express something related to cats, but that can also be applied to other, non-cat related classes. What if I want to know how old my cat is? Sure, I could create set_age() and get_age() functions within my Cat class, but then this would restrict that function only to cats. 

What if I have a Fridge class, and I want to know how old my fridge is? Well, sheeeeit, it just makes no sense to use the HowOld() function from Cats and apply it to Fridge! Sure, I could engage in some coding gymnastics and hack the Cat class to access the set_age() and get_age()  functions from within my Fridge class. But... if I do that... well, that would be like farting in a lift. Fun times.

Or I could write more set_age() and get_age()  functions in the Fridge class. That would mean repeating code, and we don’t want to do that either! 

How about creating a new general helper class called Helpers, and including the set_age() and get_age()  functions there? That way, the Cat and Fridge classes can call the set_age() and get_age() functions from the Helper class. That would solve the problem of code reuse, as well as allowing for a logical single-point of access. But that isn’t ideal either, because in each scenario a Helper class will need to be either instantiated or called as a singleton.

Drum roll...

Enter, traits.

Traits are perfect for this type of situation. They can be implemented easily within a class with the use keyword. In our example above, we implement the Helpers trait in the Cat and Fridge classes. By implementing Helpers in Cat and Fridge, we can now call any of the functions declared in Helper by calling them directly from Cat or Fridge.

What’s more, we can implement as many traits as we like within a class. 

Again, notice how we’ve prefixed Helpers with the word Includes. This is because in our Helpers traits file, we’ve applied the Includes namespace. This necessitates prefixing Helpers with Includes. 

Why is this useful? For one, it helps us avoid name collisions, especially in larger projects. For example, if you have a file named Helper.php in the Includes folder, and another Helper.php file in the Helpers folder, applying namespaces to each of these will help keep the two concerns separate if we want to include them simultaneously during execution.

In our particular example, our use of namespace gives us the added benefit of allowing us to conveniently auto-load our files without implementing more conditional statements than we have to.

Now, let’s take a look at our index.php file:

      <?php
        spl_autoload_register(function ($class_name) {
          $class_name = str_replace("\\","/",$class_name);
          echo $class_name . ".php"; //can be removed
          echo "<br>";  //can be removed
            require_once $class_name . ".php";
        });


        $cat = new Cat();
        $fridge = new Fridge();

      ?>

      <html>
      <head>
        <title>Fridge and Cat Ages</title>

      </head>

      <body>
        <?php
          $cat->set_age(22);

          echo "Cat's age: " . $cat->get_age();

          echo "<br>";

          $fridge->set_age(2);

          echo "Fridge's age: " . $fridge->get_age();
        ?>
      </body>

      </html>


Let’s unpack this, like it’s Xmas. First up, you’ll notice the spl_autoload_register function. In this case, the spl_autoload_register calls an anonymous lambda function (ooh, jargon, again! Fancy way of saying a function that doesn’t have a name). 

The  spl_autoload_register is the magic that allows us to load our files automatically, without having to implement reams of ‘require’, ‘include’, or ‘require_once’ statements. Basically, the spl_autoload_register function allows PHP to figure out when you’re instantiating a class, sets $class_name to the name of your class, and then allows you to do what you please with the $class_name variable. The function loops until all iterations of the $class_name variable have concluded.  

But here’s the magic. It also detects what you’ve loaded inside a class. Remember how we implemented our Helper trait in our Cat and Fridge classes? spl_autoload_register will also register that under $class_name, allowing us to work with that file as well. Wow! Amazing! I like! 

This is where our namespace comes in. Because the Helper.php file lives in the Includes folder, the spl_autoload_register makes use of the entire namespace, and sets the name as such. 

In our example, you’ll see that there is a str_replace function. This is because namespaces make use of the back slash. For file system calls we need to use the forward slash, otherwise we will get a ﻿No such file or directory warning, and the file will fail to load. The str_replace() function substitutes backslashes with forward slashes.

Our spl_autoload_register() function also echoes out some of the resulting class names. This allows you to get an idea of how the function works.
We then go on to instantiate a $cat and a $fridge object from their respective classes. We don’t need to manually include the files thanks to our fantastic, handy-dandy spl_autoload_register function. Woo-hoo!

Finally, the nitty-gritty.

In our <body> section, we set the age of our cat at 22 years. We set the age of our fridge at 2 years. But notice that the set_age() function lives in the Helpers trait file, not in our Fridge or Cat classes. Most excellent! With traits, we’ve managed to incorporate external functions that work just like native class functions, allowing us to manipulate private class variables. If that isn’t heaven, then I don’t know what is! Party time! 

Conclusion

With great power comes great responsibility. Please use traits wisely. You can easily abuse them, and your code will end up as a great mass of unrelated Frankenstein spaghetti functions. In my mind, traits are a bit of a hack, and I love PHP, OO in PHP leaves a lot to be desired with its lack of multiple inheritance.

Likewise, be careful with namespaces. You can nest namespaces indefinitely like this:

      I\Am\A\NameSpace\And\I\Kind\Of\Suck

Just because you can, it doesn’t mean you should. Try to keep your namespace structure shallow and easy-to-understand. 

As for spl_autoload_register(). Well, it’s good stuff!

Have fun!

AWMOB
