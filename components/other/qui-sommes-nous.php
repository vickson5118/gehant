<?php 
namespace components\other;

require_once($_SERVER["DOCUMENT_ROOT"]."/src/Utilisateur.php");

session_start (); 

?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Qui sommes-nous? | Gehant</title>
		<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/css.php"); ?>
		<link rel="stylesheet" href="/inc/css/other/qui-sommes-nous.css" type="text/css" />
	</head>
	
	<body>
		
		<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/header.php"); ?>
	
		<div class="container-fluid who-container">
		
			<div class="who-container-head">
					<div class="who-head-bg">
						<div class="container">
							<h2>Nous sommes Gehant</h2>
    						<p>
    							Gehant est un cabinet créé en 2013 spécialisé dans la formation, le coaching, le team building, le conseil et la 
    								conduite de changement. Animé par une équipe de consultants de haut niveau. 
    								Nos services s’adressent aux entreprises (PME, PMI, ou grands groupes internationaux), ainsi qu’aux 
    								particuliers et avec le même niveau d’engagement. <br /><br />
    								En plus des activités principales du cabinet axées sur le développement du potentiel humain
    									(management, leadership, communication et relations humaines, gestion et développement d’équipes….),
    									le cabinet offre des formations et de l’accompagnement sur bien d’autres sujets afin de vous offrir toutes 
    									les ressources nécessaires vous permettant d’atteindre le niveau d’épanouissement et de performance souhaité.
    							</p>
						</div>
					</div>
				</div>
		
			<div class="container">
				
				<div class="row pres-container">
					
					<div class="col-md-4">
						<img src="/inc/images/icones/who/mission.png" alt="Notre mission" />
    					<h2>Notre mission</h2>
        				<p>Accompagner les organisations dans le but d’atteindre un niveau supérieur de performance ; 
        						Aider les hommes à exploiter leur plein potentiel pour produire plus de résultats en vue d’impacter positivement leur 
        						communauté.</p>
					</div>
					
					<div class="col-md-4">
						<img src="/inc/images/icones/who/vision.png" alt="Notre vision" />
						<h2>Notre vision</h2>
						<p>Être le partenaire de référence en matière de transformation et d’accompagnement à la performance.
							Aider les hommes et les femmes à vivre pleinement leur potentiel pour une vie personnelle et professionnelle plus réussie.
						</p>
					</div>
						
				
					<div class="col-md-4">
						<img src="/inc/images/icones/who/equipe.png" alt="Notre équipe" />
						<h2>Notre équipe</h2>
        				<p>Notre équipe est composée de coachs et de formateurs professionnels. 
        						Pour certains sujets spécifiques, le cabinet GEHANT a recours à des professionnels expérimentés choisis
        						avec les plus grands soins.</p>
					</div>
	
				</div>
				</div>		
				
				<div class="container-fluid valeurs-container">
				
				
					<div class="valeurs-container-desc">
						<div class="container">
    						<h2>Nos valeurs : RAGE</h2>
        					<p>Votre plan de développement se fait avec vous. Nous croyons que « nos limites sont celles que nous nous fixons ». 
        							Nous travaillons donc avec engagement et sélectionnons avec minutie chaque contenu de nos formations et de notre 
        							accompagnement. Nos séances de coaching se construisent autour de votre réussite. 
        					</p>
        					
        					<ol>
        						<li>
        							<b>Respect</b> : C’est le respect des autres et des engagements. Nous promettons un accompagnement sur mesure à chaque personne 
            						et à chaque organisation qui nous sollicite. Dans toutes nos interactions, nous traitons chaque coaché, 
            						formé ou demandeur d’accompagnement de manière respectueuse et valorisante. Pour garantir le succès de notre 
            						accompagnement, vous veillons au respect de tous nos engagements.
            					</li>
        						<li>
        							<b>Audace</b> : L’audace est la force qui fait dépasser les limites. Cette audace nous fait croire que tout est possible pourvu que nous y croyions. 
            						Cette valeur aussi elle qui nous pousse à nous surpasser pour offrir plus de services à plus de personnes.
            					</li>
        						<li>
        							<b>Grandeur</b> : Il y a de la grandeur en chaque être humain. Il suffit de la voir puis de l’extérioriser pour réaliser des choses 
            						au-delà de ce que nous pouvons imaginer. Dans notre accompagnement, nous poussons nos clients à rêver grand 
            						et à travailler à faire de chaque rêve une réalité.
            					</li>
        						<li>
        							<b>Excellence</b> : Nous nous investissons à produire le meilleur, à vous accompagner avec les meilleurs outils. 
            						Nos séances de formation, de coaching et nos activités de team building respectent notre charte.
            					</li>
        					</ol>
    					
						</div>
					
    				</div>
				</div>
						
						
				<div class="activites-container container-fluid">
    				<h2>Nos activités</h2>
    				
    				<div class="container">
    				
    					<div class="row one-activity-container">
    						<div id="coaching"></div>
            				<div class="col-md-8 one-activity-detail">
            					<h3>Coaching</h3>
            					<p>Le coaching est l'accompagnement d'une personne ou d’une organisation vers son autonomie dans une 
            							dynamique de changement. C'est un processus d'entretiens individuels et d’actions pratiques qui reposent 
            							sur une relation de collaboration. Cet accompagnement est centré sur des objectifs à atteindre et structuré 
            							de façon qu'il permette à une personne coachée d’atteindre les objectifs souhaités. <br />
            							La méthode : L’écoute attentive et bienveillante, le dialogue sincère et responsable pour amener à une mobilisation 
            							plus efficace des talents et au développement des capacités personnelles de façon à transformer les obstacles présents 
            							en opportunités d’évolution.
            						<a href="/contactez-nous">Contactez-nous</a>
            					</p>
            				</div>
            				<img src="/inc/images/icones/activites/coaching.jpg" alt="Coaching" class="col-md-4"/>
        				</div>
        				
        				
        				<div class="row one-activity-container-reverse">
        					<div id="formation"></div>
        					<img src="/inc/images/icones/activites/formation.jpeg" alt="Formation" class="col-md-4" />
        					<div class="col-md-8 one-activity-detail">
        						<h3>Formation</h3>
                				<div>
                    				<p> GEHANT conçoit et propose des formations sur mesure, alliant méthodologies et mises en situation.
                                        Nous enrichissons régulièrement les thèmes et contenus de nos formations afin de répondre aux besoins
                    				évolutifs de nos clients.</p>
                				
                    				<div>
                    					
                        				Nos formations sont interactives :
                        				<ul>
                        					<li>Présentations théoriques des méthodes, concept et outils ;</li>
                        					<li>Cas pratiques inspirées des situations des auditeurs ;</li>
                        					<li>Ateliers collaboratifs en sous-groupes (workshop)</li>
                        					<li>Exercices pratiques et restitution</li>
                        				</ul>
                    				</div>
                    				
                    				<div>
                    					Nous faisons le suivi et l’évaluation des acquis :
                    					<ul>
                    						<li>Auto-évaluation</li>
                    						<li>Evaluation de la formation par les participants (quiz)</li>
                    						<li>Remise d’une attestation en fin de formation</li>
                    					</ul>
                    				</div>
                					<a href="/domaines">Nos formations</a>
                				</div>
        					</div>
        					
        				</div>
        				
        				<div class="row one-activity-container">
        					<div id="conseil"></div>
        					<div class="col-md-8 one-activity-detail">
        						<h3>Conseil</h3>
                				<p>Nos activités de conseil du cabinet sont orientées vers la gestion de vos ressources humaines et le développement de 
                						leur potentiel. Nous vous conseillons les meilleures activités en vue de garantir un environnement de travail favorable 
                							à l’épanouissement et à la performance de vos équipes.
                							Nous travaillons ensemble, nous coconstruisons un plan et vous accompagnons afin de le réaliser
                							<a href="/contactez-nous">Contactez-nous</a>
                					</p>
        					</div>
        					<img src="/inc/images/icones/activites/conseil.jpg" alt="Conseil" class="col-md-4" />	
        				</div>
        				
        				
        				<div class="row one-activity-container-reverse">
        					<div id="team-building"></div>
        					<img src="/inc/images/icones/activites/team-building.jpg" alt="Team building" class="col-md-4" />	
        					<div class="col-md-8 one-activity-detail">
        						<h3>Team building</h3>
                				<div>Le team building est l’organisation d’activités destinées à développer ou créer des compétences de groupe. 
                						En plus simple, c’est réunir son personnel ou les membres d’une équipe de travail autour de valeurs communes afin de :
                					<ul>
                						<li>Développer le potentiel de l’équipe</li>
                						<li>Renforcer l’esprit d’équipe et la solidarité</li>
                						<li>Développer le sens du partage des objectifs</li>
                						<li>Aider les membres de l’équipe à mieux se connaître</li>
                						<li>Valoriser la communication interpersonnelle et briser les barrières entre entités de l’entreprise</li>
                					</ul>
                					<a href="/contactez-nous">Contactez-nous</a>
                				</div>
        					</div>
        				</div>
        				
        				<div class="row one-activity-container">
        					<div id="conduite-changement"></div>
        					<div class="col-md-8 one-activity-detail">
        						<h3>Conduite du changement</h3>
                				<p>La conduite du changement est l'ensemble des opérations effectuées au sein d'une organisation pour lui permettre de 
                						s'adapter au changement et à l'évolution de l'environnement. En cas de projets structurant (transformation digitale, 
                						nouvelles orientations stratégiques...), selon les défis auxquels vous êtes confrontés ou selon vos nouvelles orientations, 
                						nous vous proposons un projet d’accompagnement afin travailler au changement de mentalité des équipes et en vue de 
                						faciliter leur adaptation aux nouvelles orientations.
                						<a href="/contactez-nous">Contactez-nous</a>
                				</p>
        						
        					</div>
        					<img src="/inc/images/icones/activites/changement.jpg" alt="Conduite du changement" class="col-md-4" />
        				</div>
				
    				</div>
    				
			</div>
			<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/footer.php"); ?>
		</div>
		
		
		
		<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/js.php"); ?>
	</body>
	
</html>