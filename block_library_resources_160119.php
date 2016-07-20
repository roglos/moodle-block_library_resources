<?php

/*
 * Name: Library Resources Block
 * Version: 1.2.4
 * Author: Paul Griffiths
 * Created: 1st November 2012
 * Main Release date: 18th January 2013
 * Last modified: 19th August 2015 - John Hill
 *
 */

	class block_library_resources extends block_base {
	
	
		/*
		 * START EDITABLE SECTION 1
		 * ************************
		 *
		 * Edit Links array to change the hyperlinks displayed in the content portion of the block
		 * Edit Footer content array to set footer image/link/text
		 *
		 */

		/*
		 * Links array
		 * Custom class variable containing link text and urls. Lines may be added, deleted or edited.
		 * There is no fixed number of links
		 *
		 * Syntax
		 * 'Link text' => 'link url',
		 * note: all editable links end with a comma except the last one.
		 *
		 */
		private $links = array(
			// Start of editable links
			'Library catalogue' => 'http://opac.glos.ac.uk/',
			'Library resources for your course' => 'https://infonet.glos.ac.uk/departments/lis/Pages/default.aspx',
			'Referencing' => 'https://infonet.glos.ac.uk/departments/lis/Pages/referencing.aspx',
			'My library account' => 'https://infonet.glos.ac.uk/departments/lis/Pages/mylibraryaccount.aspx',
			'LIS copyright statement (Pdf)' => 'https://infonet.glos.ac.uk/departments/lis/Documents/Moodle%20Copyright%20Statement.pdf'
			// End of editable links
		);

		
		/* Footer content array
		 * custom class variable containing information for footer content
		 *
		 * Syntax
		 * 'name' => 'value'
		 * Only change the value
		 * 
		 * type:     set to none, text, link, image or image+link depending on requirements
		 * filename: set to image name including file extension. Do not include directory path. If no image is required set to empty ie ''
		 * text:     set to text, link name or image alt and title text depending on requirements
		 * url:      set to link url, If no link is required set to empty ie ''
		 *
		 */
		private $footer_content = array(
			// Start of editable values
			'type' => 'image+link',
			'filename' => 'askalibrarian.png',
			'text' => 'Chat to Library staff or leave a message',
			'url' => 'http://insight.glos.ac.uk/departments/lis/Pages/Ask.aspx?utm_source=moodle&utm_medium=button&utm_campaign=LIS_ask'
			// End of editable values
		);
		
		/* Reading list switch
		 * Turn reading list on or off
		 *
		 * Syntax
		 * 'name' => 'value'
		 * Only change the value 
		 *
		 * set to on to display reading list link, set to off to omit reading list link
		 *
		 */
		private $reading_list = 'off';
		
		/* END EDITABLE SECTION 1
		 *************************/
		 

		// custom class variable used to build the block content
		private $temp_builder = '';
							  
		public function init() {
		/*
			* START EDITABLE SECTION 2
			* ************************
			*
			* Edit the diplsy title of the block
			* 
			* Syntax
			* $custom_title = 'My Title';
			* change My Title to desired text, changes are global across all instances
			*
			*/
		
			// title setting
			$this->title = 'Library Resources';
		
			/* END EDITABLE SECTION 2
			 *************************/
		}
		
		// custom method to generate html content from $links
		private function build_links() {
			
			// ensure temp_builder is set to an empty string
			if ($this->temp_builder !== '') {
				$this->temp_builder = '';
			}
			
			// build html content
			$this->temp_builder .= '<ul>';
			foreach ($this->links as $key => $value) {
				$this->temp_builder .= '<li><a href="' . $value . '" target="_blank">' . $key . '</a></li>';
			}
			
			// build reading list link if required
			if ($this->reading_list === 'on') {		
				global $COURSE;
				$module_code = strstr($COURSE->shortname,'_',true);
				// test for a valid module code
				if ($module_code !== false && strlen($module_code) < 8 && ctype_alpha($module_code[0]) && ctype_alnum($module_code) && !ctype_alpha($module_code) && !ctype_digit($module_code)) { 
					$this->temp_builder .= '<li><a href="http://aleph.glos.ac.uk/F?func=find-e&request=' . $module_code . '&find_code=CNO&local_base=UOG30" target="_blank">Module Reading List (Library Cat)</a></li>';
				}
			}
			
			// finish building html content
			$this->temp_builder .= '</ul>';

			// return html content
			return $this->temp_builder;
		}
		
		// custom method to generate footer content from $footer_content
		private function build_footer() {
		
			// ensure temp_builder is set to an empty string
			if ($this->temp_builder !== '') {
				$this->temp_builder = '';
			}

			// build footer content depending on type set in $footer_content
			switch ($this->footer_content['type']) {
				case 'text':
					$this->temp_builder .= $this->footer_content['text'];
					break;
				case 'link':
					$this->temp_builder .= '<a href="' . $this->footer_content['url'] . '">';
					$this->temp_builder .= $this->footer_content['text'];
					$this->temp_builder .= '</a>';
					break;
				case 'image':
					$this->temp_builder .= '<img src="' . $CFG->dirroot . '/moodle/blocks/' . $this->name() . '/images/' . $this->footer_content['filename'] . '" alt="' . $this->footer_content['text'] . '" title="' . $this->footer_content['text'] . '" />';
					break;
				case 'image+link':
					$this->temp_builder .= '<a href="' . $this->footer_content['url'] . '" target="_blank">';
					$this->temp_builder .= '<img src="' . $CFG->dirroot . '/moodle/blocks/' . $this->name() . '/images/' . $this->footer_content['filename'] . '" alt="' . $this->footer_content['text'] . '" title="' . $this->footer_content['text'] . '" />';
					$this->temp_builder .= '</a>';
					break;
			}
			
			// return footer content
			return $this->temp_builder;
		}
		
		public function get_content() {
			
			if ($this->content !== null) {
				return $this->content;
			}
			
			$this->content         = new stdClass;
			$this->content->text   = $this->build_links();
			$this->content->footer = $this->build_footer();
			return $this->content;
		}

	}
	
?>