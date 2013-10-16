<?php  echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t('Help'), false);?>
<ol style="padding-left:0">
	<li><a href="#about-this-documentation">About This Documentation</a></li>
	<li><a href="#forms">Forms</a>
		<ol style="padding-left:0;list-style-type:lower-alpha">
			<li><a href="#settings">Settings</a></li>
			<li><a href="#fields">Fields</a></li>
			<li><a href="#notifications">Notifications</a></li>
			<li><a href="#results">Results</a></li>
		</ol>
	</li>
	<li><a href="#styles">Styles</a></li>
	<li><a href="#tools">Tools</a>
		<ol style="padding-left:0;list-style-type:lower-alpha">
			<li><a href="#import-data-from-csv">Import Data from CSV</a></li>
			<li><a href="#form-converter">Form Converter</a></li>
		</ol>
	</li>
</ol>


<h2 id="about-this-documentation"><span>About This Documentation</span></h2>

<p>Although Advanced Forms is a very powerful forms add-on for Concrete5, most of it is pretty straightforward.  For the sake of clarity in this document, we've left the easy stuff out (we're assuming you can figure out that in order to create a new form you click "Create a New Form"). This document is here to help explain the more complicated things like styles, e-commerce integration, and search indexing.</p>
<p>Of course if you do have trouble any aspect, feel free to post to the Questions & Discussion under this Add-On in the Concrete5 Marketplace, or contact us at sixeightmedia@gmail.com.</p>


<h2 id="forms"><span>Forms</span></h2>


<h3 id="settings">Settings</h3>

<h4>Notify User Upon Approval</h4>
<p>This option, found under the "Other Settings" tab of the Setting dialog box allow you to send an email to the user that submitted the form (the user must have been logged in) when a record is approved.  </p>

<h4>Payment Gateway</h4>
<p>The payment gateway field is used on e-commerce forms.  You must have a payment gateway installed in your root /element folder in order to use this feature.  Full documentation regarding payment gateways coming soon.</p>

<h4>Maximum Order Price</h4>
<p>When using an e-commerce form, this setting is used to specify a maximum amount to charge.</p>

<h4>Message Before Processing E-Commerce</h4>
<p>Submitting an e-commerce form is a two step process.  The first step is filling out the form.  Once the form is filled out and submitted, they are taken to a confirmation screen where there see the full price and the message specified here before the order is sent to the payment gateway.</p>

<h4>Data Display Integration</h4>
<p>Advanced Forms is highly integrated with the Data Display package.  You will see this tab on the form Settings dialog box only if you have the Data Display package installed.  The settings on this tab are used to let you generate new pages each time an record is added to a particular form.</p>

<hr />

<h3 id="fields">Fields</h3>

<h4>Field Types</h4>
<p>The first step in creating a new field is to select a field type.  If you want to change a field type after it has been selected, you simply click on the field type label at the top of the Edit Field dialog box.</p>

<h4>Searchability</h4>
<p>The "Searchable?" option lets you specify whether or not data in a particular field will be index by the Advanced Forms search engine.  This includes searching from the "Records" page and when displaying data on the front-end with Data Display integration.</p>

<h4>URL Parameter</h4>
<p>The URL parameter option let's you specify a default value for a form field with the URL.  For example if I want a dropdown box to have a particular field selected by default, and I want to specify that dynamically on the page, I could set "dropdown" as the URL parameter.  Then, when I go to the form on the front end of my site I can specify the value in the URL as follows:  www.example.com/path/to/form?dropdown=Value</p>

<hr />

<h3 id="notifications">Notifications</h3>

<h4>User Confirmation</h4>
<p>User Confirmation lets you send an email to the user that submitted a form when they submit it. Simply specify the form field that will be used as the email address, as well as a from name, submit and message.</p>

<h4>Conditional Notifications</h4>
<p>Conditional Notifications are used to send emails to different users depending on the form fields specified.</p>

<hr />

<h3 id="results">Results</h3>

<h4>Adding a Record</h4>
<p>To add a record from the dashboard, simply click Add a Record and fill out the form.</p>

<h4>Searching/Indexing</h4>
<p>In order for forms to be searchable, they must be indexed.  Indexing can be setup as a Job within Concrete5 (Dashboard -> System & Maintenance) so that it doesn't have to be run manually.</p>

<h4>Cache</h4>
<p>Whenever data from a form is displayed on your site (even from the dashboard), that data is cached in order to increase performance of your site.  The cache can be disabled from the Data Display Integration tab of the form settings.   The cache is automatically cleared when records are added, changed, or removed.  You may also clear the cache manually from the Records page, or automatically via a Concrete5 Scheduled Job.</p>

<h4>Approving Records</h4>
<p>You may approve/unapprove records on the Records page.  This can be used for integration with Data Display, or in conjunction with the "Notify User Upon Approval" option in a form's settings.</p>

<h4>Exporting Records</h4>
<p>You can export a form's data using the export option at the bottom of the listing of form records.  Data exports in CSV format.</p>



<h2 id="styles"><span>Styles</span></h2>

<p>Form data is displayed in a tableless layout and is fully customizable via CSS.  Custom styles can be created on the Styles page.  When you create a custom style, there are multiple classes that are used to style specific parts of the form.  Each class has a description of specifically which aspect of the form it controls.  CSS code properties and values can be added to each class.</p>


<h2 id="tools"><span>Tools</span></h2>

<h3 id="import-data-from-csv">Import Data from CSV</h3>
<p>Advanced Forms allows you to import a CSV file with form data.  Once you import the file, you will be asked to map each column to a particular field of the form.</p>
<h3 id="form-converter">Form Converter</h3>
<p>The form converter allows you to convert any core form blocks to Advanced Forms.  It converts the data as well.</p>
