<?php
/**
 * Textos de la landing en EN y ES.
 *
 * Los textos en inglés provienen del "Manual corporativo para creación de
 * página web institucional" v1.0 (mayo 2026, Dirección General). La versión
 * en español es traducción sobria de esos mismos textos; el bloque
 * "Connected, but distinct" usa las redacciones oficiales del manual en
 * ambos idiomas.
 */
function icor_strings( $lang = 'en' ) {
	$en = array(
		'meta_description' => 'ICOR Research & Innovation Center is an interdisciplinary center focused on clinical research, digital surgical workflows, technological development, and responsible innovation transfer in customized orthognathic surgery.',
		'nav_aria'         => 'Main navigation',
		'nav_about'        => 'About',
		'nav_research'     => 'Research',
		'nav_team'         => 'Team',
		'nav_collab'       => 'Collaboration',
		'nav_contact'      => 'Contact',

		'hero_kicker'  => 'Research · Innovation · Customized Orthognathic Surgery',
		'hero_title'   => 'Advancing customized orthognathic surgery through research, digital workflows, and technological innovation.',
		'hero_sub'     => 'ICOR Research & Innovation Center is an interdisciplinary center focused on clinical research, surgical innovation, digital workflows, technological development, and responsible innovation transfer in customized orthognathic surgery.',
		'hero_cta1'    => 'Explore our work',
		'hero_cta2'    => 'Collaborate with us',
		'hero_cta3'    => 'Follow us on LinkedIn',

		'about_kicker' => 'About the center',
		'about_title'  => 'About ICOR Research & Innovation Center',
		'about_body'   => 'ICOR Research & Innovation Center is an interdisciplinary research and innovation center focused on advancing customized orthognathic surgery. Our work connects clinical expertise, scientific research, digital surgical workflows, biomedical engineering, clinical validation, technological development, intellectual property, and responsible innovation transfer. We aim to contribute to the global development of customized surgical innovation by building bridges between clinicians, researchers, engineers, technology partners, and international institutions.',

		'distinct_kicker' => 'Connected, but distinct',
		'distinct_title'  => 'Two units, one purpose',
		'distinct_body'   => 'ICOR Research & Innovation Center operates in close connection with Clínica ICOR, while maintaining a distinct focus on scientific research, technological development, clinical validation, intellectual property, innovation transfer, and international collaboration. Clínica ICOR focuses on patient care and customized orthognathic surgery, while the Research & Innovation Center focuses on advancing knowledge, methods, technologies, and future solutions for the field.',
		'distinct_col1_title' => 'Clínica ICOR',
		'distinct_col1_body'  => 'Patient care and customized orthognathic surgery: clinical practice, surgical experience, and real-world clinical challenges.',
		'distinct_col2_title' => 'ICOR Research & Innovation Center',
		'distinct_col2_body'  => 'Scientific research, digital workflows, clinical validation, technological development, intellectual property, and responsible innovation transfer.',

		'research_kicker' => 'Research & Innovation Areas',
		'research_title'  => 'What we work on',
		'research_lead'   => 'Our lines of research and innovation connect clinical practice with science, engineering, and technology.',
		'research_areas'  => array(
			array( 'Customized Orthognathic Surgery', 'Research and innovation focused on patient-specific approaches, surgical planning, and customized solutions for orthognathic surgery.' ),
			array( 'Digital Surgical Workflows', 'Study, optimization, and validation of digital workflows for customized surgical planning and execution.' ),
			array( 'Clinical Validation & Accuracy', 'Evaluation of surgical accuracy, clinical outcomes, and alignment between virtual planning and surgical execution.' ),
			array( 'Biomedical Technology Development', 'Development and assessment of technological solutions, digital tools, devices, and processes for customized surgical innovation.' ),
			array( 'Intellectual Property & Innovation Transfer', 'Identification, protection, and transfer of innovation opportunities emerging from clinical and technological development.' ),
			array( 'International Collaboration', 'Collaboration with researchers, clinical centers, universities, companies, and innovation partners working in digital surgery and patient-specific healthcare.' ),
		),

		'team_kicker' => 'Team',
		'team_title'  => 'An interdisciplinary team',
		'team_lead'   => 'Clinical leadership, research, biomedical engineering, and institutional communication working together.',
		'team'        => array(
			array( 'Ignacio Cuevas', 'Director General', 'Leads the strategic direction of the center, its research agenda, and its national and international collaboration network.' ),
			array( 'Dr. Luis Quevedo', 'Clinical Leadership', 'Brings surgical expertise and clinical judgment to guide research priorities and clinical validation.' ),
			array( 'Research Team', 'Clinical Research', 'Coordinates study design, data collection, and scientific output across the center’s research lines.' ),
			array( 'Biomedical Engineering', 'Surgical Customization', 'Develops and evaluates patient-specific digital planning, devices, and technological solutions.' ),
			array( 'Jesús Gireaud', 'Communication & Positioning', 'Leads institutional communication, digital presence, and scientific positioning of the center.' ),
		),

		'collab_kicker' => 'Collaboration',
		'collab_title'  => 'We are open to international collaboration',
		'collab_body'   => 'ICOR Research & Innovation Center seeks to collaborate with researchers, clinical centers, universities, technology companies, biomedical innovation groups, and institutions working on customized surgery, digital surgical planning, patient-specific technologies, clinical validation, and responsible innovation transfer.',
		'collab_items'  => array(
			'Customized orthognathic surgery',
			'Digital surgical workflows',
			'Patient-specific surgical solutions',
			'Clinical validation and accuracy assessment',
			'Biomedical technology development',
			'Data-driven surgical innovation',
			'Intellectual property and technology transfer',
		),
		'collab_cta' => 'Start a conversation',

		'contact_kicker'  => 'Contact',
		'contact_title'   => 'Contact us',
		'contact_body'    => 'Tell us who you are and what kind of collaboration you have in mind. We will get back to you shortly.',
		'contact_email_label' => 'Institutional email',
		'form_name'       => 'Full name',
		'form_org'        => 'Organization / institution',
		'form_email'      => 'Email',
		'form_type'       => 'Type of collaboration',
		'form_type_opts'  => array(
			'Research collaboration',
			'Clinical center / multicenter studies',
			'Industry & technology',
			'University / academia',
			'Funding & institutional',
			'Other',
		),
		'form_message'    => 'Message',
		'form_submit'     => 'Send message',
		'form_ok'         => 'Thank you. Your message has been received — we will contact you soon.',
		'form_error'      => 'Something went wrong. Please try again or write to us directly by email.',

		'footer_note'    => 'ICOR Research & Innovation Center operates in close connection with Clínica ICOR, with a distinct focus on research, technological development, and responsible innovation transfer. This website is institutional and is not a channel for patient care.',
		'footer_contact' => 'Contact',
	);

	$es = array(
		'meta_description' => 'ICOR Research & Innovation Center es un centro interdisciplinario enfocado en investigación clínica, flujos de trabajo quirúrgicos digitales, desarrollo tecnológico y transferencia responsable de innovación en cirugía ortognática customizada.',
		'nav_aria'         => 'Navegación principal',
		'nav_about'        => 'El centro',
		'nav_research'     => 'Investigación',
		'nav_team'         => 'Equipo',
		'nav_collab'       => 'Colaboración',
		'nav_contact'      => 'Contacto',

		'hero_kicker'  => 'Investigación · Innovación · Cirugía Ortognática Customizada',
		'hero_title'   => 'Impulsamos la cirugía ortognática customizada a través de investigación, flujos digitales e innovación tecnológica.',
		'hero_sub'     => 'ICOR Research & Innovation Center es un centro interdisciplinario enfocado en investigación clínica, innovación quirúrgica, flujos de trabajo digitales, desarrollo tecnológico y transferencia responsable de innovación en cirugía ortognática customizada.',
		'hero_cta1'    => 'Conoce nuestro trabajo',
		'hero_cta2'    => 'Colabora con nosotros',
		'hero_cta3'    => 'Síguenos en LinkedIn',

		'about_kicker' => 'Sobre el centro',
		'about_title'  => 'Sobre ICOR Research & Innovation Center',
		'about_body'   => 'ICOR Research & Innovation Center es un centro interdisciplinario de investigación e innovación enfocado en el avance de la cirugía ortognática customizada. Nuestro trabajo conecta experiencia clínica, investigación científica, flujos quirúrgicos digitales, ingeniería biomédica, validación clínica, desarrollo tecnológico, propiedad intelectual y transferencia responsable de innovación. Buscamos contribuir al desarrollo global de la innovación quirúrgica customizada construyendo puentes entre clínicos, investigadores, ingenieros, socios tecnológicos e instituciones internacionales.',

		'distinct_kicker' => 'Conectados, pero distintos',
		'distinct_title'  => 'Dos unidades, un propósito',
		'distinct_body'   => 'ICOR Research & Innovation Center opera en estrecha vinculación con Clínica ICOR, manteniendo un foco diferenciado en investigación científica, desarrollo tecnológico, validación clínica, propiedad intelectual, transferencia de innovación y colaboración internacional. Clínica ICOR se enfoca en la atención de pacientes y cirugía ortognática customizada, mientras que el centro de investigación se orienta a desarrollar conocimiento, métodos, tecnologías y soluciones futuras para el área.',
		'distinct_col1_title' => 'Clínica ICOR',
		'distinct_col1_body'  => 'Atención de pacientes y cirugía ortognática customizada: práctica clínica, experiencia quirúrgica y desafíos clínicos reales.',
		'distinct_col2_title' => 'ICOR Research & Innovation Center',
		'distinct_col2_body'  => 'Investigación científica, flujos digitales, validación clínica, desarrollo tecnológico, propiedad intelectual y transferencia responsable de innovación.',

		'research_kicker' => 'Áreas de investigación e innovación',
		'research_title'  => 'En qué trabajamos',
		'research_lead'   => 'Nuestras líneas de investigación e innovación conectan la práctica clínica con la ciencia, la ingeniería y la tecnología.',
		'research_areas'  => array(
			array( 'Cirugía ortognática customizada', 'Investigación e innovación enfocadas en enfoques específicos por paciente, planificación quirúrgica y soluciones customizadas para cirugía ortognática.' ),
			array( 'Flujos quirúrgicos digitales', 'Estudio, optimización y validación de flujos de trabajo digitales para la planificación y ejecución quirúrgica customizada.' ),
			array( 'Validación clínica y precisión', 'Evaluación de la precisión quirúrgica, los resultados clínicos y la correspondencia entre planificación virtual y ejecución quirúrgica.' ),
			array( 'Desarrollo de tecnología biomédica', 'Desarrollo y evaluación de soluciones tecnológicas, herramientas digitales, dispositivos y procesos para la innovación quirúrgica customizada.' ),
			array( 'Propiedad intelectual y transferencia', 'Identificación, protección y transferencia de oportunidades de innovación que emergen del desarrollo clínico y tecnológico.' ),
			array( 'Colaboración internacional', 'Colaboración con investigadores, centros clínicos, universidades, empresas y socios de innovación en cirugía digital y salud personalizada.' ),
		),

		'team_kicker' => 'Equipo',
		'team_title'  => 'Un equipo interdisciplinario',
		'team_lead'   => 'Liderazgo clínico, investigación, ingeniería biomédica y comunicación institucional trabajando en conjunto.',
		'team'        => array(
			array( 'Ignacio Cuevas', 'Director General', 'Lidera la dirección estratégica del centro, su agenda de investigación y su red de colaboración nacional e internacional.' ),
			array( 'Dr. Luis Quevedo', 'Liderazgo clínico', 'Aporta experiencia quirúrgica y criterio clínico para orientar las prioridades de investigación y la validación clínica.' ),
			array( 'Equipo de investigación', 'Investigación clínica', 'Coordina el diseño de estudios, la recolección de datos y la producción científica de las líneas del centro.' ),
			array( 'Ingeniería biomédica', 'Customización quirúrgica', 'Desarrolla y evalúa planificación digital específica por paciente, dispositivos y soluciones tecnológicas.' ),
			array( 'Jesús Gireaud', 'Comunicación y posicionamiento', 'Lidera la comunicación institucional, la presencia digital y el posicionamiento científico del centro.' ),
		),

		'collab_kicker' => 'Colaboración',
		'collab_title'  => 'Estamos abiertos a la colaboración internacional',
		'collab_body'   => 'ICOR Research & Innovation Center busca colaborar con investigadores, centros clínicos, universidades, empresas tecnológicas, grupos de innovación biomédica e instituciones que trabajen en cirugía customizada, planificación quirúrgica digital, tecnologías específicas por paciente, validación clínica y transferencia responsable de innovación.',
		'collab_items'  => array(
			'Cirugía ortognática customizada',
			'Flujos quirúrgicos digitales',
			'Soluciones quirúrgicas específicas por paciente',
			'Validación clínica y evaluación de precisión',
			'Desarrollo de tecnología biomédica',
			'Innovación quirúrgica basada en datos',
			'Propiedad intelectual y transferencia tecnológica',
		),
		'collab_cta' => 'Iniciar una conversación',

		'contact_kicker'  => 'Contacto',
		'contact_title'   => 'Contáctanos',
		'contact_body'    => 'Cuéntanos quién eres y qué tipo de colaboración tienes en mente. Te responderemos a la brevedad.',
		'contact_email_label' => 'Correo institucional',
		'form_name'       => 'Nombre completo',
		'form_org'        => 'Organización / institución',
		'form_email'      => 'Correo electrónico',
		'form_type'       => 'Tipo de colaboración',
		'form_type_opts'  => array(
			'Colaboración en investigación',
			'Centro clínico / estudios multicéntricos',
			'Industria y tecnología',
			'Universidad / academia',
			'Financiamiento e institucional',
			'Otro',
		),
		'form_message'    => 'Mensaje',
		'form_submit'     => 'Enviar mensaje',
		'form_ok'         => 'Gracias. Hemos recibido tu mensaje — te contactaremos pronto.',
		'form_error'      => 'Algo salió mal. Inténtalo de nuevo o escríbenos directamente por correo.',

		'footer_note'    => 'ICOR Research & Innovation Center opera en estrecha vinculación con Clínica ICOR, con un foco diferenciado en investigación, desarrollo tecnológico y transferencia responsable de innovación. Este sitio es institucional y no constituye un canal de atención de pacientes.',
		'footer_contact' => 'Contacto',
	);

	return 'es' === $lang ? $es : $en;
}
