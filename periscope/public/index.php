<?php
    $page_title = "Home";
    require_once("header.php");
?>



    <section id="content">
        <nav id="menu-units">
            <ul id="menu-unit-buttons">
                <li class="button"><a href="browse.php">Browse Units</a></li>
                <li class="button"><a href="keyword.php">Keyword Search</a></li>
                <li class="button"><a href="new-unit.php">New Unit</a></li>
            </ul>			
        </nav>
        <hr>
        <article id="intro" class="clearfix">
            <h2>Introduction</h2>
            <section id="intro-text" class="intro-content" align = "justify">
                <h2>What is Periscope?</h2>
                    <h3>An answer to a question</h3>
                        <p><em>"How can we map our curriculum the way we want, rather than how some software company says we should?"</em></p>
                        <p>When it comes to the noble goal of curriculum mapping, schools are often at a disadvantage. High quality systems for outlining and studying curriculum are often prohibitively expensive. Moreover, they can be overly complex. <b>Periscope</b> aims to provide schools with a simple, yet powerful tool for recording and analyzing the instruction happening in their classrooms.</p>
                    <h3>What you want it to be</h3>
                        <p><b>Periscope</b> is open-source, meaning you are free to modify and redistribute the code that makes it work however you wish. Want to change the Assets associated with Units? Go ahead! Want to add more detail, or integrate standards like Common Core? No problem. Then you can contribute the changes you've made with other schools. When everyone contributes, everyone wins.</p>
            </section>
            <section id="intro-video" class="intro-content">
                <h2>Take a Tour</h2>
                <p>Video goes here.</p>
            </section>

        </article>

    </section>




<?php require_once("footer.php"); ?>