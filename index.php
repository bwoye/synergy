<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/myindex.css">
    <script src="js/jquery.js"></script>
    <script src="js/mmslogin.js"></script>
    <title>Dashboard</title>
</head>

<body>
    <div class="container">
        <div class="titles">
            <div class="headers1">
                <span>Business Synergies Dashboard</span>
                <span>Quick summaries</span>
            </div>            
        </div>

        <div id="datex"></div>
        <div class="loginforms">
            <span><strong>Grantee Login</strong>
                <form method="post" action="php/login.php">
                    <input type="text" id="guserid" name="userid" placeholder="userid">
                    <input type="password" id="gkpass" name="kpass" placeholder="password">
                    <input type="hidden" id="utype" name="utype" value="grantee">
                    <input type="hidden" id="tzone2" name="tzone">
                    <button id="gentry" type="submit" name="submit">login</button>
                </form>
            </span>

            <span><strong>Admin Login</strong>
                <form method="post" action="php/login.php">
                    <input type="text" id="userid" name="userid" placeholder="userid">
                    <input type="password" id="kpass" name="kpass" placeholder="passowrd">
                    <input type="hidden" id="utype" name="utype" value="admin">
                    <input type="hidden" id="tzone" name="tzone">
                    <button id="aentry" type="submit" name="submit">login</button>
                </form>
            </span>
        </div>
        <div class="details">
            <div class="contentx">
               <div class="budgetsummaries"></div> 
               <div class="onlytable"></div>
            </div>
            <div class="tellstory">
                <h2>Success Stories</h2>
                <div class="myimages">
                    <div class="realone">
                        <img src="succespics/transformers.jpg" width="300">
                    </div>

                    <div class="subdetails" style="width:300px;">
                        <h4>Electrical Control and Switchgear Ltd (ECS Ltd)</h4>
                        <span>

                            <p>“Prior to the SDF skilling programme the company had spent about 30,000 USD on paying
                                for designs for electricity transformers, but since the time we trained our staff,
                                the designs are now done in house, which is a big saving to the company”: Joshua
                                Sendawula Engineer ECS.</p>

                            <p>“The level of our employees’ competence and technical compliance has since greatly
                                increased after undergoing the PSFU supported training” Abu A. Mugobero Projects
                                Engineer ECS.</p>

                        </span>
                        <p>ECS is a company trading in manufacturing of Power Distribution Transformers, Electrical
                            main switch gear, and panels. The company used to work with Deshi an Indian company that
                            used to send designs of transformers that were then assembled at their factory in
                            Kawempe. The company has since received a grant from PSFU where their employee have been
                            trained in the design and manufacturing of power distribution transformers. </p>



                    </div>
                </div>
                <div class="myimages">
                    <div class="realone">
                       
                    </div>
                    <div class="subdetails">
                        <h3>Graphic Systems Ltd</h3>
                        <span>
                            <p>“we used to have those two bins over flowing with material off cuts but now you can
                                clearly see that we just have a handful, which is a clear sign of the increased
                                level of competency of our workers”</p>
                        </span>
                        <p>At Graphic Systems the PSFU skilling intervention is clearly showing results of improving
                            productivity but has also influenced the company to now take action for capacity
                            building and skilling of its staff.</p>

                        <span>
                            <p>“We now have a training calendar before we did not see a lot of need for skilling our
                                staff, but after participating in the PSFU project we realized the benefits it had
                                brought to the company” HR Executive Graphic Systems</p>
                        </span>

                        <p>Prior to the SDF training for employee under the apparel section the level of raw
                            material wastage was high. In a testimony of the section supervisor she states </p>
                    </div>
                </div>
                <div class=" myimages">
                    <div class="realone">
                        <img src="succespics/lugangu.jpg" width="300">
                    </div>

                    <div class="subdetails" style="width:300px;">
                        <h4>
                            LUGANGU SAVINGS AND CREDIT COOPERATIVE SOCIETY (SACCO) Oct 2108
                        </h4>
                        <p>Joan Erimirwa is a member of Lugangu SACCO in Mayuge district. In 2011, Joan started
                            making savings, when the sacco started shjoined and attended training in modern
                            production skills. Her SACCO received a grant from Private Sector Foundation Uganda
                            through its skills development facility (SDF) for capacity building in modern skills for
                            coffee production processing and marketing. Over the last six months; Joan has acquired
                            skills in coffee field production, postharvest handling, and coffee processing for value
                            addition as well as effective marketing. She explains that “the skills acquired have
                            enabled me to improve my field coffee management practices for increased production and
                            productivity”. “Previously I was not able to raise even one basin of red cherries from
                            one coffee tree, but that now she is able to harvest 3-4 basins of red cherries per
                            tree”. This she attributes it to improved field management practices like disease and
                            pest control, pruning and de-suckering, as well as soil and water management practices.
                            Below is Joan harvesting from her fairly well managed coffee shamba.</p>

                        <p>Through the PSFU-SDF grant, Joan reduced postharvest handling losses and improved the
                            coffee quality. She says she has acquired skills of wet and dry processing of coffee and
                            that she has been able to dry coffee to the required moisture content on raised drying
                            trays and on tarpaulins off the bear ground in order to maintain coffee quality. Below
                            Joan displays her skills in drying of Robusta coffee on tarpaulins provided through SDF.
                            Joan presented 270 kg of dried Robusta coffee (kiboko) for processing and the output was
                            160kgs of FAQ/ Kase. This she said was able to fetch her a higher price than the
                            ordinary kiboko coffee that she was previously being offered by traders and middlemen
                            whom she says were exploiting farmers ignorance due to lack of knowledge and skills.
                            Joan was thus able to sell a kg of processed FAQ coffee at Ug. Shs. 4500 over and above
                            the Ug. Shs. 1800= she was previously earning from the sale of unprocessed kiboko
                            coffee. She was thus able to realize Ug. Shs. 720,000 from her processed coffee up from
                            the Ug. Shs. 486,000 she would have received if she had sold unprocessed kiboko coffee.
                            This additional income Joan says is attributed to the skills acquired during the
                            training and that this has helped her to add value to her coffee by having it processed
                            before marketing.</p>
                    </div>
                </div>
                <div class=" myimages">
                    <div class="realone">
                        <img src="succespics/sheema.jpg" width="300">
                    </div>

                    <div class="subdetails" style="width:300px;">
                        <h4>
                            KASOLO FOUNDATION
                        </h4>
                        <span>
                            <p> don’t know how my life would have been without SDF and Kasolo Foundation. I have
                                plans of supporting myself to skill further in makeup and bridal dressing to
                                diversify and boost my business… I have also been</p>
                        </span>
                        <p>Nabyanzi Resty a young 20 year old lady from Kyotera had taken years without attending
                            school because of lack of school fees participated in the first skilling project with
                            Kasolo Foundation, where she received skilling in hair dressing. She has since started
                            her own saloon business, where she also employed one of her fellow trainee with whom
                            they are now working. She is also currently skilling another young lady at her saloon in
                            hairdressing. </p>


                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const options = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };

        var starting = new Date().toLocaleDateString("en-UK", options);
        document.querySelector('#datex').innerHTML = starting;

        var kzone = new Date();
        var jkzone = kzone.getTimezoneOffset();
        document.getElementById('tzone').value = -1 * jkzone;
        document.getElementById('tzone2').value = -1 * jkzone;
        //document.getElementById('etzone').value = -1 * jkzone;
        // alert(document.getElementById('tzone').value);
    </script>
</body>

<!-- <div class="row">
            <div class="">
                <div class="contentx"></div>
            </div>
            <div class="col-12 col-sm-4 col">
                <div class="tellstory">
                    <h3>Success Stories</h3>
                    <div class="myimages">
                        <div class="realone">
                            <img src="succespics/transformers.jpg" width="300">
                        </div>

                        <div class="subdetails" style="width:300px;">
                            <h4>Electrical Control and Switchgear Ltd (ECS Ltd)</h4>
                            <span>

                                <p>“Prior to the SDF skilling programme the company had spent about 30,000 USD on paying
                                    for designs for electricity transformers, but since the time we trained our staff,
                                    the designs are now done in house, which is a big saving to the company”: Joshua
                                    Sendawula Engineer ECS.</p>

                                <p>“The level of our employees’ competence and technical compliance has since greatly
                                    increased after undergoing the PSFU supported training” Abu A. Mugobero Projects
                                    Engineer ECS.</p>

                            </span>
                            <p>ECS is a company trading in manufacturing of Power Distribution Transformers, Electrical
                                main switch gear, and panels. The company used to work with Deshi an Indian company that
                                used to send designs of transformers that were then assembled at their factory in
                                Kawempe. The company has since received a grant from PSFU where their employee have been
                                trained in the design and manufacturing of power distribution transformers. </p>



                        </div>
                    </div>
                    <div class="myimages">
                        <div class="realone">
                           
                        </div>
                        <div class="subdetails">
                            <h3>Graphic Systems Ltd</h3>
                            <span>
                                <p>“we used to have those two bins over flowing with material off cuts but now you can
                                    clearly see that we just have a handful, which is a clear sign of the increased
                                    level of competency of our workers”</p>
                            </span>
                            <p>At Graphic Systems the PSFU skilling intervention is clearly showing results of improving
                                productivity but has also influenced the company to now take action for capacity
                                building and skilling of its staff.</p>

                            <span>
                                <p>“We now have a training calendar before we did not see a lot of need for skilling our
                                    staff, but after participating in the PSFU project we realized the benefits it had
                                    brought to the company” HR Executive Graphic Systems</p>
                            </span>

                            <p>Prior to the SDF training for employee under the apparel section the level of raw
                                material wastage was high. In a testimony of the section supervisor she states </p>
                        </div>
                    </div>
                    <div class=" myimages">
                        <div class="realone">
                            <img src="succespics/lugangu.jpg" width="300">
                        </div>

                        <div class="subdetails" style="width:300px;">
                            <h4>
                                LUGANGU SAVINGS AND CREDIT COOPERATIVE SOCIETY (SACCO) Oct 2108
                            </h4>
                            <p>Joan Erimirwa is a member of Lugangu SACCO in Mayuge district. In 2011, Joan started
                                making savings, when the sacco started shjoined and attended training in modern
                                production skills. Her SACCO received a grant from Private Sector Foundation Uganda
                                through its skills development facility (SDF) for capacity building in modern skills for
                                coffee production processing and marketing. Over the last six months; Joan has acquired
                                skills in coffee field production, postharvest handling, and coffee processing for value
                                addition as well as effective marketing. She explains that “the skills acquired have
                                enabled me to improve my field coffee management practices for increased production and
                                productivity”. “Previously I was not able to raise even one basin of red cherries from
                                one coffee tree, but that now she is able to harvest 3-4 basins of red cherries per
                                tree”. This she attributes it to improved field management practices like disease and
                                pest control, pruning and de-suckering, as well as soil and water management practices.
                                Below is Joan harvesting from her fairly well managed coffee shamba.</p>

                            <p>Through the PSFU-SDF grant, Joan reduced postharvest handling losses and improved the
                                coffee quality. She says she has acquired skills of wet and dry processing of coffee and
                                that she has been able to dry coffee to the required moisture content on raised drying
                                trays and on tarpaulins off the bear ground in order to maintain coffee quality. Below
                                Joan displays her skills in drying of Robusta coffee on tarpaulins provided through SDF.
                                Joan presented 270 kg of dried Robusta coffee (kiboko) for processing and the output was
                                160kgs of FAQ/ Kase. This she said was able to fetch her a higher price than the
                                ordinary kiboko coffee that she was previously being offered by traders and middlemen
                                whom she says were exploiting farmers ignorance due to lack of knowledge and skills.
                                Joan was thus able to sell a kg of processed FAQ coffee at Ug. Shs. 4500 over and above
                                the Ug. Shs. 1800= she was previously earning from the sale of unprocessed kiboko
                                coffee. She was thus able to realize Ug. Shs. 720,000 from her processed coffee up from
                                the Ug. Shs. 486,000 she would have received if she had sold unprocessed kiboko coffee.
                                This additional income Joan says is attributed to the skills acquired during the
                                training and that this has helped her to add value to her coffee by having it processed
                                before marketing.</p>
                        </div>
                    </div>
                    <div class=" myimages">
                        <div class="realone">
                            <img src="succespics/sheema.jpg" width="300">
                        </div>

                        <div class="subdetails" style="width:300px;">
                            <h4>
                                KASOLO FOUNDATION
                            </h4>
                            <span>
                                <p> don’t know how my life would have been without SDF and Kasolo Foundation. I have
                                    plans of supporting myself to skill further in makeup and bridal dressing to
                                    diversify and boost my business… I have also been</p>
                            </span>
                            <p>Nabyanzi Resty a young 20 year old lady from Kyotera had taken years without attending
                                school because of lack of school fees participated in the first skilling project with
                                Kasolo Foundation, where she received skilling in hair dressing. She has since started
                                her own saloon business, where she also employed one of her fellow trainee with whom
                                they are now working. She is also currently skilling another young lady at her saloon in
                                hairdressing. </p>


                        </div>
                    </div>
                </div>
            </div>
        </div> -->

</html>