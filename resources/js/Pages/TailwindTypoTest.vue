<template>
    <div class="flex space-between justify-center items-center">
        <div class=" prose lg:prose-xl prose-headings:underline">
            <p><span style="background-color:rgb(255,255,255);color:rgb(71,85,105);font-size:22px;">"Until now, trying to
                    style
                    an article, document, or blog post with Tailwind has been a tedious task that required a keen eye for
                    typography and a lot of complex custom CSS."</span></p>
            <p><span style="background-color:rgb(255,255,255);color:rgb(51,65,85);font-size:18px;">By default, Tailwind
                    removes
                    all of the default browser styling from paragraphs, headings, lists and more. This ends up being really
                    useful for building application UIs because you spend less time undoing user-agent styles, but when you
                </span><i>really are</i><span style="background-color:rgb(255,255,255);color:rgb(51,65,85);font-size:18px;">
                    just trying to style some content that came from a rich-text editor in a CMS or a markdown file, it can
                    be
                    surprising and unintuitive.</span></p>
            <p><span style="background-color:rgb(255,255,255);color:rgb(51,65,85);font-size:18px;">We get lots of complaints
                    about it actually, with people regularly asking us things like:</span></p>
            <blockquote>
                <p><span style="background-color:rgb(255,255,255);color:rgb(15,23,42);font-size:18px;"><i>Why is Tailwind
                            removing the default styles on my </i></span><i><strong>h1</strong></i><span
                        style="background-color:rgb(255,255,255);color:rgb(15,23,42);font-size:18px;"><i> elements? How do I
                            disable this? What do you mean I lose all the other base styles too?</i></span></p>
            </blockquote>
            <h2><span style="background-color:rgb(255,255,255);color:rgb(15,23,42);font-size:21px;"><i><strong>What to
                            expect
                            from here on out</strong></i></span></h2>
            <p>&nbsp;</p>
            <p><span style="background-color:rgb(255,255,255);color:rgb(51,65,85);font-size:18px;">What follows from here is
                    just a bunch of absolute nonsense I've written to dogfood the plugin itself. It includes every sensible
                    typographic element I could think of, like </span><strong>bold text</strong><span
                    style="background-color:rgb(255,255,255);color:rgb(51,65,85);font-size:18px;">, unordered lists, ordered
                    lists, code blocks, block quotes, </span><i>and even italics</i><span
                    style="background-color:rgb(255,255,255);color:rgb(51,65,85);font-size:18px;">.</span></p>
            <p>&nbsp;</p>
            <p>It's important to cover all of these use cases for a few reasons:</p>
            <ol>
                <li>We want everything to look good out of the box.</li>
                <li>Really just the first reason, that's the whole point of the plugin.</li>
                <li>Here's a third pretend reason though a list with three items looks more realistic than a list with two
                    items.</li>
            </ol>
            <p>Now we're going to try out another header style.</p>
            <p>Now I'm going to show you an example of an unordered list to make sure that looks good, too:</p>
            <ul>
                <li>So here is the first item in this list.</li>
                <li>In this example we're keeping the items short.</li>
                <li>Later, we'll use longer, more complex list items.</li>
            </ul>
            <p>And that's the end of this section.</p>
            <p><br><strong>What about nested lists?</strong></p>
            <p>Nested lists basically always look bad which is why editors like Medium don't even let you do it, but I guess
                since some of you goofballs are going to do it we have to carry the burden of at least making it work.</p>
            <ol>
                <li><strong>Nested lists are rarely a good idea.</strong>
                    <ul>
                        <li>You might feel like you are being really "organized" or something but you are just creating a
                            gross
                            shape on the screen that is hard to read.</li>
                        <li>Nested navigation in UIs is a bad idea too, keep things as flat as possible.</li>
                        <li>Nesting tons of folders in your source code is also not helpful.</li>
                    </ul>
                </li>
                <li><strong>Since we need to have more items, here's another one.</strong>
                    <ul>
                        <li>I'm not sure if we'll bother styling more than two levels deep.</li>
                        <li>Two is already too much, three is guaranteed to be a bad idea.</li>
                        <li>If you nest four levels deep you belong in prison.</li>
                    </ul>
                </li>
                <li><strong>Two items isn't really a list, three is good though.</strong>
                    <ul>
                        <li>Again please don't nest lists if you want people to actually read your content.</li>
                        <li>Nobody wants to look at this.</li>
                        <li>I'm upset that we even have to bother styling this.</li>
                    </ul>
                </li>
            </ol>
            <p><br><strong>There are other elements we need to style</strong></p>
            <p>I almost forgot to mention links, like <a href="https://tailwindcss.com/"><u>this link to the Tailwind CSS
                        website</u></a>. We almost made them blue but that's so yesterday, so we went with dark gray, feels
                edgier.</p>
            <p>We even included table styles, check it out:</p>
            <figure class="table">
                <table>
                    <thead>
                        <tr>
                            <th><strong>Wrestler</strong></th>
                            <th><strong>Origin</strong></th>
                            <th><strong>Finisher</strong></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Bret "The Hitman" Hart</td>
                            <td>Calgary, AB</td>
                            <td>Sharpshooter</td>
                        </tr>
                        <tr>
                            <td>Stone Cold Steve Austin</td>
                            <td>Austin, TX</td>
                            <td>Stone Cold Stunner</td>
                        </tr>
                        <tr>
                            <td>Randy Savage</td>
                            <td>Sarasota, FL</td>
                            <td>Elbow Drop</td>
                        </tr>
                        <tr>
                            <td>Vader</td>
                            <td>Boulder, CO</td>
                            <td>Vader Bomb</td>
                        </tr>
                        <tr>
                            <td>Razor Ramon</td>
                            <td>Chuluota, FL</td>
                            <td>Razor's Edge</td>
                        </tr>
                    </tbody>
                </table>
            </figure>
            <p>We also need to make sure inline code looks good, like if I wanted to talk about
                <strong>&lt;span&gt;</strong>
                elements or tell you the good news about <strong>@tailwindcss/typography</strong>.
            </p>
            <!-- <h1>Hello World</h1>
        <ol>
            <li>it is</li>
            <li>not wha</li>
            <li>you&nbsp;</li>
            <li>think&nbsp;</li>
            <li>and it&nbsp;</li>
            <li>is use full&nbsp;</li>
            <li>by me</li>
        </ol>
        <p><span style="background-color:hsl(120,75%,60%);">We will be greeen.</span></p>
        <figure class="table">
            <table>
                <tbody>
                    <tr>
                        <td>name</td>
                        <td>age</td>
                        <td>email</td>
                    </tr>
                    <tr>
                        <td>mg mg</td>
                        <td>21</td>
                        <td>mgmg</td>
                    </tr>
                    <tr>
                        <td>Hla Hla</td>
                        <td>21</td>
                        <td>hlahla</td>
                    </tr>
                </tbody>
            </table>
        </figure> -->

            <h2>This assumes that within the 'email1' field, there is a key named 'value' that contains the actual email
                values. Adjust the key accordingly based on the actual structure of the 'email1' array.</h2>
            <ul>
                <li>If you're still facing issues or if the&nbsp;</li>
                <li>structure of the 'email1' array is more complex,&nbsp;</li>
                <li>it might be beneficial to print the entire array&nbsp;</li>
                <li>using <strong>print_r</strong> for debugging purposes. You can use the following code temporarily:</li>
            </ul>
            <p>&nbsp;</p>
            <blockquote>
                <p>array(<br>&nbsp; &nbsp;'name' =&gt; 'email1',<br>&nbsp; &nbsp;'label' =&gt;
                    'LBL_EMAIL_ADDRESS',<br>&nbsp; &nbsp;'customCode' =&gt; '{php}print_r($fields.email1);{/php}',<br>),</p>
            </blockquote>
            <p>&nbsp;</p>
            <figure class="table">
                <table>
                    <tbody>
                        <tr>
                            <td>City</td>
                            <td>Township</td>
                            <td>Quater</td>
                        </tr>
                        <tr>
                            <td>Yangon</td>
                            <td>Tharkeyta</td>
                            <td>New</td>
                        </tr>
                    </tbody>
                </table>
            </figure>
            <p>&nbsp;</p>
            <p><span style="color:hsl(0,0%,0%);">This will print the entire 'email1' <s>array, and from there</s>, you can
                    <sub>identify the correct key</sub> to access the values. Remember to remove or comment out such
                    debugging code once you've identified the correct structure.This will print the entire 'email1' array,
                    and from there, you can <sup>identify</sup> the correct key to access the values. Remember to remove or
                    comment out such debugging code once you've identified the correct structure.This will </span><span
                    style="background-color:hsl(210,75%,60%);color:hsl(0,75%,60%);">print the entire 'email1' array, and
                    from there, you can identify the correct key</span><span style="color:hsl(0,0%,0%);"> to access the
                    values. Remember to remove or comment out </span><span
                    style="background-color:hsl(90,75%,60%);color:hsl(0,0%,0%);">such debugging code</span><span
                    style="color:hsl(0,0%,0%);"> once you've identified the correct structure.This will print the entire
                    'email1' array, and </span><span style="color:hsl(0,75%,60%);">from there</span><span
                    style="color:hsl(0,0%,0%);">, you can identify the correct key to access the values. Remember to
                    remove or <i><strong>comment out</strong></i> such debugging code once you've identified the correct
                    structure.This will print the entire 'email1' array, and from there, you can identify the correct key to
                    access the values. Remember to remove or comment out such </span><a href="https://google.com"><span
                        style="color:hsl(0,0%,0%);">debugging</span></a><span style="color:hsl(0,0%,0%);"> code once
                    you've identified the correct structure.</span></p>
            <p>&nbsp;</p>
            <ol>
                <li><span style="background-color:rgb(33,33,33);color:rgb(236,236,236);font-size:16px;">This will print
                        the entire 'email1' array, and from there,&nbsp;</span></li>
                <li><span style="background-color:rgb(33,33,33);color:rgb(236,236,236);font-size:16px;">you can identify
                        the correct key to access the values.&nbsp;</span></li>
                <li><span style="background-color:rgb(33,33,33);color:rgb(236,236,236);font-size:16px;">Remember to remove
                        or comment out such&nbsp;</span></li>
                <li><span style="background-color:rgb(33,33,33);color:rgb(236,236,236);font-size:16px;">debugging code
                        once you've identified the correct structure.</span></li>
            </ol>
        </div>
    </div>

    <!-- <div>
        <table class="border-collapse w-full border border-slate-400 dark:border-slate-500 bg-white dark:bg-slate-800 text-sm shadow-sm">
            <thead>
                <tr>
                    <th class="w-1/2 border border-slate-300 dark:border-slate-600 font-semibold p-4 text-slate-900 dark:text-slate-200 text-left">State</th>
                    <th class="w-1/2 border border-slate-300 dark:border-slate-600 font-semibold p-4 text-slate-900 dark:text-slate-200 text-left">City</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="border border-slate-300 dark:border-slate-700 p-4 text-slate-500 dark:text-slate-400">Indiana</td>
                    <td class="border border-slate-300 dark:border-slate-700 p-4 text-slate-500 dark:text-slate-400">Indianapolis</td>
                </tr>
                <tr>
                    <td class="border border-slate-300 dark:border-slate-700 p-4 text-slate-500 dark:text-slate-400">Ohio</td>
                    <td class="border border-slate-300 dark:border-slate-700 p-4 text-slate-500 dark:text-slate-400">Columbus</td>
                </tr>
                <tr>
                    <td class="border border-slate-300 dark:border-slate-700 p-4 text-slate-500 dark:text-slate-400">Michigan</td>
                    <td class="border border-slate-300 dark:border-slate-700 p-4 text-slate-500 dark:text-slate-400">Detroit</td>
                </tr>
            </tbody>
        </table>
    </div> -->
</template>

<style></style>