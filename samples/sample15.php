<?php
$title = 'About cache';
$body = <<< EOF
<p>
	Every time in the constructor You'll specify that the resulting code must be cached this is what happens:
	<ul>
		<li>The class caculate the cyclic redundancy check of the contents of the file in which has been instantiated,</li>
		<li>now the class checks his own path looking for a file named stricty with that code,</li>
		<li>if the file is found, a flag cause all called methods to skip the work, and returning the contents of the matching file when you finally call the <code>render()</code> function,</li>
		<li>if the file is not found, the same flag will instead let all functions to do their work and will cause the writing on that file with the code produced just before the <code>render()</code> function outputs the code</li>
	</ul>
	So if you mean to use that feature to speed up the response, be sure to instantiate the class in a separate file, just to be sure that non-sense cache files are produced for nothing.
</p>
EOF;
include('tpl_vuoto.php');
