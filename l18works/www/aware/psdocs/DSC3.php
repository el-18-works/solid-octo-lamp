<?php require_once("psdocs.php"); ?>
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 3.0//EN">
<html><head>
<title>DSC</title>
<title> Document Structuring Conventions</title>
<META NAME="Author" CONTENT="Satlin Luckxa.">
<META NAME="Publisher" CONTENT="Lucifer, inc.">
<META NAME="Publisher-Email" CONTENT="s4instar@yahoo.ne.jp">
<META NAME="Content-Language" CONTENT="zh-CS">
<meta name="generator" content="vim">
<style>
<?=css_minimum()?>
<?=css_psdocs()?>
</style>
</head>
<body>
<H1>PostScript Language Document Structuring Conventions Specification 1</H1>
<A NAME="Requirement"></A>
<B>6 Requirement Conventions</B><BR>
The requirement conventions are comments that suggest document manager action.
Some of these comments list the resources needed or supplied by the document,
delimit those resources if they are supplied, and specify the inser-tion
point for those resources if they are needed. Other comments deal with printer-specific
features (listing requirements, delimiting portions of and indicating insertion
points for printer specific code) and are used in tandem with the setpagedevice
operators or statusdict operators, as well as the PostScript printer description
(PPD) files.<BR>
Note Use of the %%Include or %%Operator comments in an environment that
does not have a document manager can result in the document being processed
incorrectly.<BR><A NAME="ReqHeader"></A>
<HR></A>
<h2>6.1 Requirement Header Comments</h2><BR>
<BR>
<A NAME="DocumentMedia"></A><B>%%DocumentMedia: </B>&lt;medianame&gt;
&lt;attributes&gt; <BR>
&lt; medianame&gt; ::= &lt; text&gt; (Tag name of the media) <BR>
&lt; attributes&gt; ::= &lt; width&gt; &lt; height&gt; &lt; weight&gt; &lt;
color&gt; &lt; type&gt; <BR>
&lt; width&gt; ::= &lt; real&gt; (Width in PostScript units) <BR>
&lt; height&gt; ::= &lt; real&gt; (Height in PostScript units) <BR>
&lt; weight&gt; ::= &lt; real&gt; (Weight in g/m 2 ) <BR>
&lt; color&gt; ::= &lt; text&gt; (Paper color) <BR>
&lt; type&gt; ::= &lt; text&gt; (Type of pre-printed form)<BR>
<BR>
This comment indicates all types of paper media (paper sizes, weight, color)
this document requires. If any of the attributes are not applicable to a
particu-lar printing situation, zeroes must be substituted for numeric parameters
and null strings must be substituted for text parameters. Each different
medium that is needed should be listed in its approximate order of descending
quan-tity used.<BR>
<BR>
<div class=code>
<CODE>%%DocumentMedia: Plain 612 792 75 white ( ) <BR>
%%+ BlueCL 612 792 244 blue CorpLogo <BR>
%%+ Tax 612 792 75 ( ) (1040)<BR>
</CODE><BR>
</div>
The preceding example indicates that the following media are needed for
this job: 
<UL>
<LI>. 8.5&quot; x 11&quot;, 20 lb. paper (Bond lbs Yen 3.76 = g/m 2 ). 
<LI>. Cover pages in blue 8.5&quot; x 11&quot;, 65 lb. paper preprinted
with the corporate logo. 
<LI>. Preprinted IRS 1040 tax forms. 
</UL>
<BR>
Note that the type attribute refers to preprinted forms only, and does not
refer to the PostScript language concept of form objects as resources. The
following keywords for the type name are defined for general use:<BR>
19HoleCerlox <BR>
ColorTransparency <BR>
CustLetterHead <BR>
Tabs <BR>
3Hole <BR>
CorpLetterHead <BR>
DeptLetterHead <BR>
Transparency <BR>
2Hole <BR>
CorpLogo <BR>
Labels <BR>
UserLetterHead<BR>
<BR>
The related %%PageMedia: comment explicitly calls for the medium that each
page requires by referring to its medianame.<BR>
<BR>
<A NAME="DocumentNeededResources"></A><B>%%DocumentNeededResources: </B>&lt;resources&gt;
| (atend)<BR>
This comment provides a list of resources the document needs-that is, resources
not contained in the document file. This comment is intended to help a document
manager decide whether further parsing of the document file is necessary
to provide these needed resources. There must be at least one corresponding
instance of the %%IncludeResource: comment for each resource this comment
lists.<BR>
The application that produces the print file must not make any assumptions
about which resources are resident in the output device; it must list all
resources the document needs. Even if it is a resource, such as the Times-Roman
font program, that exists in nearly all implementations, it must appear
here. A resource must not be listed if it is not used anywhere in the document.<BR>
As a general rule, different types of resources should be listed on separate
lines using the %%+ comment, as illustrated in the following example:<BR>
<BR>
<div class=code>
<CODE>%%DocumentNeededResources: font Times-Roman Helvetica StoneSerif <BR>
%%+ font Adobe-Garamond Palatino-Roman <BR>
%%+ file /usr/lib/PostScript/logo.ps <BR>
%%+ procset Adobe_Illustrator_abbrev 1.0 0 <BR>
%%+ pattern hatch bubbles <BR>
%%+ form (corporate order form) <BR>
%%+ encoding JIS<BR>
<BR>
</CODE>
</div>
<A NAME="DocumentSuppliedResources"></A><B>%%DocumentSuppliedResources: </B>&lt;resources&gt;
| (atend)<BR>
The %%DocumentSuppliedResources: comment contains extra information for
document managers designed to store and reuse the resources, and provides
helpful directories of the resources contained in the print file. This comment
lists all resources that have been provided in the document print file.
There is a %%BeginResource: and %%EndResource pair for each resource in
this list. It is assumed that all resources on the %%DocumentSuppliedResources:
list are mutually exclusive of those resources found on the %%DocumentNeededResources:
list.<BR>
<BR>
<A NAME="DocumentPrinterRequired"></A><B>%%DocumentPrinterRequired: </B>&lt;print&gt;
&lt;prod&gt; [&lt;vers&gt; [&lt;rev&gt;] ] <BR>
&lt; print&gt; ::= &lt; text&gt; (Printer name and print zone) <BR>
&lt; prod&gt; ::= &lt; text&gt; (Product string or nickname) <BR>
&lt; vers&gt; ::= &lt; real&gt; (Version number) <BR>
&lt; rev&gt; ::= &lt; uint&gt; (Revision number)<BR>
This comment indicates that the PostScript language instructions in the
document are intended for a particular printer, which is identified by its
network printer name, nickname, or product string. The printer can optionally
be identified by its version and revision strings, as defined by the printer's
PPD file or as returned by the product, version, and revision operators.<BR>
<BR>
%%DocumentPrinterRequired: can be used to request a particular printer in
a highly networked environment where that printer may be more convenient
or to override document manager defaults and prevent re-routing of the docu-ment.
It can also be used if the PostScript language file itself contains printer-specific
elements. This last case should rarely be necessary, as most docu-ments
requiring particular features of a PostScript printer can provide requirement
conventions indicating a need for that feature, rather than require a particular
printer. Then, if other printers are available that have the necessary features,
the document may still be printed as desired. The following example unconditionally
routes the document to a printer called SEVILLE in the network's &quot;Sys_Marketing&quot;
zone:<BR>
<BR>
%%DocumentPrinterRequired: (<a href="mailto:SEVILLE@Sys_Marketing">SEVILLE@Sys_Marketing</a>) ( )<BR>
<BR>
If the nickname of the printer is used (this is often necessary to differentiate
among different models of printers), the version/revision numbers that are
part of the nickname should be ignored.<BR>
For example, the product name for a series of printers may be (SpeedyLaser).
There are several models of SpeedyLaser printers, the SL300, SL600, and
SL1200. The nicknames of these printers are (SL300 Version 47.2), (SL600
Version 48.1), and (SL1200 Version 49.4). To specify the need for a SL600
printer, the nickname (excluding the version number) should be used. For
example:<BR>
<BR>
%%DocumentPrinterRequired: ( ) (SL600)<BR>
The version and revision numbers in this comment should be used infre-quently.<BR>
<BR>
<A NAME="DocumentNeededFiles"></A><B>%%DocumentNeededFiles: </B>{ 
 &lt;filename&gt;
... } | (atend)<BR>
The comment %%DocumentNeededFiles: lists the files a document description
needs. Each file mentioned in this list appears later in the document as
the argument of an %%IncludeFile: comment. It is assumed that files on the
%%DocumentNeededFiles: list do not include those appearing on the %%DocumentSuppliedFiles:
file list.<BR>
<BR>
Note This comment is provided for backward compatibility and may be discontinued
in later versions of the DSC. Use the more general comment %%DocumentNeededResources:
instead.<BR>
<BR>
<A NAME="DocumentSuppliedFiles"></A><B>%%DocumentSuppliedFiles: </B>{
 <p>

 
 &lt;filename&gt;
... } | (atend)<BR>
The comment %%DocumentSuppliedFiles: lists the files in a document description.
Each file mentioned in this list appears later in the document in the context
of a %%BeginFile: and %%EndFile: comment construct. It is assumed that files
on the %%DocumentSuppliedFiles: list do not include those appearing on the
%%DocumentNeededFiles: file list.<BR>
Note This comment is provided for backward compatibility and may be discontinued
in later versions of the DSC. Use the more general comment %%DocumentSuppliedResources:
instead.<BR>
<BR>
<A NAME="DocumentFonts"></A><B>%%DocumentFonts: </B>{
 <p>

 
 &lt;fontname&gt; ...
} | (atend)<BR>
This comment indicates that the print job uses all fonts listed. In particular,
there is at least one invocation of the findfont or findresource operator
for each of the font names listed. The application producing the print file
should not make any assumptions about which fonts are resident in the printer
(for example, Times-Roman). Note that the list of font names for %%DocumentFonts:
should be the union of the %%DocumentNeededFonts: and %%DocumentSuppliedFonts:
font lists. If the list of font names exceeds the 255 characters-per-line
limit, the %%+ comment should be used to extend the line.<BR>
Note This comment is provided for backward compatibility and may be discontinued
in later versions of the DSC. Use the more general comments %%DocumentNeededResources:
and %%DocumentSuppliedResources: instead.<BR>
<BR>
<A NAME="DocumentNeededFonts"></A><B>%%DocumentNeededFonts: </B>{
 <p>

 
 &lt;fontname&gt;
... } | (atend)<BR>
This comment provides a list of fonts the document requires and are not
contained in the document file. It is assumed that fonts on the %%DocumentNeededFonts:
list do not appear on the %%Document-SuppliedFonts: font list. It is also
assumed that there is at least one corresponding instance of the %%IncludeFont:
comment for each font listed in this section.<BR>
Note This comment is provided for backward compatibility and may be discontinued
in later versions of the DSC. Use the more general comment %%DocumentNeededResources:
instead.<BR>
<BR>
<A NAME="DocumentSuppliedFonts"></A><B>%%DocumentSuppliedFonts: </B>{
 <p>

 
 &lt;fontname&gt;
... } | (atend)<BR>
This comment provides a list of font files that have been provided in the
document print file as downloaded fonts. It is assumed that fonts on the
%%DocumentSuppliedFonts: list do not appear on the %%DocumentNeededFonts:
font list. There is at least one corresponding %%BeginFont: and %%EndFont
pair in the document description for each of the listed font names.<BR>
Note This comment is provided for backward compatibility and may be discontinued
in later versions of the DSC. Use the more general comment %%DocumentSuppliedResources:
instead.<BR>
<BR>
<A NAME="DocumentProcSets"></A><B>%%DocumentProcSets: </B>{
 <p>

 
 &lt;procname&gt;
... } | (atend)<BR>
This comment provides a list of all procsets referenced in the document.
Its use is similar to the %%DocumentFonts: comment. The list of procsets
for %%DocumentProcSets: should be the union of the %%DocumentNeededProcSets:
and %%DocumentSuppliedProcSets: procset lists. If the list of procset names
exceeds the 255 characters-per- line limit, the %%+ comment should be used
to extend the line.<BR>
Note This comment is provided for backward compatibility and may be discontinued
in later versions of the DSC. Use the more general %%DocumentNeededResources:
and %%DocumentSuppliedResources: comments instead.<BR>
<BR>
<A NAME="DocumentNeededProcSets"></A><B>%%DocumentNeededProcSets: </B>{
 <p>

 
 &lt;procname&gt;
... } | (atend)<BR>
This comment indicates that the document needs the listed procsets. It is
assumed that procsets on the %%DocumentNeededProcSets: list do not appear
on the %%DocumentSuppliedProcSets: procset list. This comment is used whenever
any %%IncludeProcSet: comments appear in the file.<BR>
Note This comment is provided for backward compatibility and may be discontinued
in later versions of the DSC. Use the more general comment %%DocumentNeededResources:
instead.<BR>
<BR>
<A NAME="DocumentSuppliedProcSets"></A><B>%%DocumentSuppliedProcSets: </B>{
 <p>

 
 &lt;procname&gt;
... } | (atend)<BR>
This comment indicates that the document contains the listed procsets. It
is assumed that procsets in the %%DocumentSuppliedProcSets: list do not
include those appearing on the %%DocumentNeededProcSets: procset list. This
comment is used whenever any %%BeginProcSet and %%EndProcSet comments appear
within the document.<BR>
Note This comment is provided for backward compatibility and may be discontinued
in later versions of the DSC. Use the more general comment %%DocumentSuppliedResources:
instead.<BR>
<BR>
<A NAME="OperatorIntervention"></A><B>%%OperatorIntervention: </B>[ &lt;password&gt;
] &lt; password&gt; ::= &lt; textline&gt;<BR>
This comment causes the document manager to block a print job in the print
queue until the printer operator releases the print job for printing. The
com-ment may contain an optional password that the print operator must supply
to release the job. This allows the printing of sensitive documents to be
delayed until the intended recipient is present at the printer to pick up
the document.<BR>
<BR>
<A NAME="OperatorMessage"></A><B>%%OperatorMessage: </B>&lt; textline&gt;<BR>
If the output device has an appropriate user interface, the %%OperatorMessage:
comment provides a message that the document manager can display on the
console before printing the job. This comment must only appear in the header
of the file.<BR>
<BR>
<A NAME="ProofMode"></A><B>%%ProofMode: </B>&lt;mode&gt; &lt; mode&gt;
::= TrustMe | Substitute | NotifyMe<BR>
This comment provides information about the level of accuracy that is required
for printing. It is intended to provide guidance to the document manager
for appropriate tactics to use when error conditions arise or when resource
and feature shortages are encountered.<BR>
The three modes may be thought of as instructions to the document manager.
If the document manager detects a resource or feature shortage, such as
a missing font or unavailable paper size, it should take action based on
these proof modes: 
<UL>
<LI>. TrustMe-Indicates the document manager should not take special action.
The intent is that the document formatting programs or the user knows more
than the document manager. For example, fonts may be available on a network
font server that the document manager does not know about<BR>
Even with a comment like %%IncludeResource:, if the %%ProofMode is TrustMe,
the printing manager should proceed even if a resource cannot be found.
The assumption is that the document can compensate for the resource not
being included. 
<LI>. Substitute-Indicates the printing manager should do the best it can
to supply missing resources with alternatives. This may mean substituting
fonts, scaling pages (or tiling) when paper sizes are not available, and
so on. This is the default proofing level and should be used if the mode
is missing from the comment or if the comment is missing from the document.
<LI>. NotifyMe-Indicates the document should not be printed if there are
any mismatches or resource shortages noted by the printing manager. For
example, when printing on an expensive color printer, if the correct font
is not available, the user probably does not want a default font. The document
manager, if it cancels the print job, should notify the user in some system-specific
manner. 
</UL>
These modes are intended for the printing manager to consider before it
prints the file, based on its own knowledge and queries of available fonts,
paper sizes, and other resources. If the file is printed, and an error occurs,
that is a separate issue.<BR>
<BR>
<A NAME="Requirements"></A><B>%%Requirements:</B> &lt;requirement&gt; [(&lt;style&gt;
...)] <BR>
... &lt; requirement&gt; ::= collate | color | duplex | faceup | fax | fold
| jog | <BR>
manualfeed | numcopies | punch | resolution | rollfed | staple <BR>
&lt; style&gt; ::= &lt; text&gt;<BR>
<BR>
This comment describes document requirements, such as duplex printing, hole
punching, collating, or other physical document processing needs. These
requirements may be activated by the document using statusdict operators
or setpagedevice, or they may be requested using the %%IncludeFeature: comment.<BR>
The requirementparameter should correspond to a specific printer feature.
The optional style parameter can be used to further describe the specifics
of the processing. For example, the punch requirement has a style to indicate
that a printer capable of 19 Hole Cerlox punching is required: punch(19).
If more than one style of requirement is necessary, the styles can be listed
in the enclosing parentheses (separated by commas) for that requirement.
For example, if both positional stapling (staple in the lower right hand
corner) and staple orientation (staple at 45 degrees) is desired, the requirement
is: staple( position, orient). This informs the document manager that the
printer printing this document must be equipped with a stapler that can
position and orient the staple.<BR>
The %%Requirements: comment can be used to determine if the printer the
user selects can meet the document's requirements. If it cannot, the document
should be rerouted to a printer that can, otherwise the document is not
pro-cessed as expected. It is the document manager's responsibility to determine
if the printer can fulfill the requirements and if the operator and/or application
should be notified of any incapability. See also the %%ProofMode: comment
for actions to take when there are no printers available that satisfy the
requirements.<BR>
<BR>
Note The %%Requirements: comment is informational only; it does not suggest
that the document manager actuate these requirements-that is, turn them
on. The PostScript language instructions in the document activate these
features.<BR>
The following keywords for the requirement parameter are defined: 
<UL>
<LI>. collate-Indicates that the document contains code that will instruct
the printer to produce collated copies (for example, 1-2-3-1-2-3-1-2-3),
rather than uncollated copies (for example, 1-1-1-2-2-2-3-3-3). If collate
is not specified, then non-collation of the document should be assumed,
except if the duplex, fold, jog, or staple requirements are specified (they
imply collation by definition). This requirement should be used in conjunction
with the numcopies requirement. 
<LI>. color-Indicates that the printer must be able to print in color. If
this option is not specified, monochrome printing is assumed to be sufficient.
<LI>. color(separation)-Indicates that the printer must be able to perform
internal color separation. If this style modifier is not specified, composite
color output is assumed to be sufficient. 
<LI>. duplex-Indicates that the document issues commands such that pages
are printed on both sides of the paper. Any printer intended to print such
a document properly must be capable of producing duplex output. 
<LI>. duplex(tumble)-Indicates a style of duplex printing in which the logical
top of the back side is rotated 180 degrees from the logical top of the
front side. A wall calendar is an example of a document that is typically
tumble duplexed. 
<LI>. faceup-Indicates that output pages are stacked face-up. If this requirement
is not specified, then the selected printer need not be capable of stacking
pages face-up. 
<LI>. fax-Indicates that the document contains segments of PostScript code
pertaining to fax devices and should be sent to a fax-capable printer. 
<LI>. fold-Indicates that the document requests that the printer fold the
resulting output. Typical style modifiers to this requirement would be letter,
z-fold, doublegate, leftgate, rightgate, and saddle. These are illustrated
in Figure 3. 
<LI>. jog-Indicates that jobs or multiple replications of the same document
are offset-stacked from one another in the output tray. The document manager
must ensure that the selected printer has the ability to offset stack job
output. 
<LI>. manualfeed-Indicates that the document requests that paper be fed
in from the manual feed slot. If this requirement is not specified, the
selected printer need not have a manual feed slot. 
<LI>. numcopies(&lt; uint&gt;)-Indicates that the document instructs the
printer to produce &lt; uint&gt; number of copies of the output. If this
requirement is not specified, a default of numcopies(1) should be assumed.
<LI>. punch-Indicates that the document specifies commands concerning hole
punching. If punch is not specified, the printer need not be capable of
punching. 
<LI>. punch(&lt; uint&gt;)-Indicates that the document contains PostScript
language instructions that cause the output to be punched with &lt; uint&gt;
number of holes. Typical values are 3-, 5-, and 19-hole (Cerlox) punching.
If there is no style modifier to the punch requirement, 3-hole punching
should be assumed to be acceptable. 
<LI>. resolution( x, y)-Indicates that the printer is set to a particular
resolution in the x and y directions. The printer manager must provide a
printer that can print in that resolution. If this requirement is not specified,
any printer resolution is acceptable. 
<LI>. rollfed-Indicates that the document issues commands specific to roll-fed
devices, such as where and when to cut the paper, how far to advance the
paper, and so on. If this requirement is not specified, the printer need
not support roll-fed paper. 
<LI>. staple-Indicates that PostScript language commands in the document
cause the output to be stapled. If staple is not specified as a requirement,
the printer need not support stapling. 
<LI>staple([ position],[ orient])-Indicates a staple position and a staple
orientation. A stapler may be able to position staples on a page in several
different locations. If the print job needs a printer stapler that performs
positioning, this should be indicated by the style keyword position. If
staple orientation is needed (for example, 0, 45, 90, or 135 degrees), the
orient style should be included with the staple requirement. If no style
modifiers are given, then simple stapling is assumed to be sufficient (top
left-hand corner). 
</UL>
<BR>
Various fold options <BR>
Z-Fold <BR>
Double Gate<BR>
Right Gate <BR>
Left Gate <BR>
Saddle <BR>
Letter<BR>
<BR>
The order of the arguments to the %%Requirements: comment is significant
and implies the order in which the operations occur in the PostScript lan-guage
code.<BR>
Example 3 shows the proper use of the %%Requirements: comment and the associated
%%Begin(End)Feature: comments. Three copies of this document will be printed
duplex; the copies will be offset in the output tray from one another.<BR>
Example 3<BR>
<BR>
<div class=code>
<CODE>%!PS-Adobe-3.0 <BR>
%%Title: (Example of requirements) <BR>
%%LanguageLevel: 2 <BR>
%%Requirements: duplex numcopies(3) jog <BR>
%%EndComments <BR>
%%BeginProlog <BR>
...Various prolog definitions... <BR>
%%EndProlog <BR>
%%BeginSetup <BR>
% For Level 1 this could have been a series of statusdict operators <BR>
%%BeginFeature: *Duplex True &lt;&lt; /Duplex true &gt;&gt; setpagedevice
<BR>
%%EndFeature /#copies 3 def <BR>
%%BeginFeature: *Jog 3 &lt;&lt; /Jog 3 &gt;&gt; setpagedevice <BR>
%%EndFeature <BR>
%%EndSetup <BR>
...Rest of the document... <BR>
%%EOF <BR>
</CODE>
<BR>
</div>
Note that in this instance, calls to setpagedevice are separated for each
fea-ture. This enables a document manager to re-route the document to a
Level 1 printer. If output is going to a Level 2 printer only, the following
could have been used:<BR>
&lt;&lt; /Duplex true /NumCopies true /Jog 3 &gt;&gt; setpagedevice<BR>
Because Level 2 feature activation is device independent, the %%Begin(End)Feature:
comments are unnecessary if the document is con-fined to Level 2 interpreters.
The %%Requirements: and the %%LanguageLevel: comments are still necessary,
however.<BR>
Note This comment lists all of the requirements for a particular job; individual
pages may use some of the requirements in different combinations. To specify
what the page requirements are for a particular page or for the whole docu-ment
(page defaults), see the %%PageRequirements: comment.<BR>
<BR>
<A NAME="VMlocation"></A><B>%%VMlocation: </B>global | local<BR>
This comment is to inform resource users if a resource can be loaded into
global or local VM. For all resource categories other than a font, the operator
findresource unconditionally executes true setglobal before executing the
file that defines the resource. This means a resource is loaded into global
VM unless false setglobal appears in the resource definition.<BR>
The creator of a resource must determine if the resource works correctly
in global VM. If it does, the resource must not execute setglobal. The resource
may wish to include the %%VMlocation: global comment. The resource is loaded
into global VM by findresource, but will be loaded into current VM under
the control of a document manager if it is explicitly downloaded.<BR>
If the resource does not work in global VM or if the creator of the resource
does not know if the resource will work reliably in global VM, the resource
must use the %%VMlocation: local comment and the following PostScript language
fragment:<BR>
<BR>
<div class=code>
<CODE>currentglobal <BR>
false setglobal <BR>
...Definition of the resource, including defineresource... <BR>
setglobal<BR>
<BR>
</CODE>
</div>
<B>%%VMusage: </B>&lt; max&gt; &lt;min&gt; <BR>
&lt; max&gt; ::= &lt; uint&gt; (Maximum VM used by resource)<BR>
&lt; min&gt; ::= &lt; uint&gt; (Minimum VM used by resource)<BR>
<BR>
The document manager can use the information supplied by this comment to
determine if the PostScript language interpreter has enough VM storage to
handle this particular resource. This comment should be used only in static
resource files, such as fonts, procsets, files, forms, and patterns, which
are all resources that rarely change and should not generally be used in
page descriptions.<BR>
max indicates the amount of VM storage this resource consumes if it is the
first resource of its type to be downloaded. min indicates the minimum amount
of VM this resource needs. The numbers may not be equal because some resources,
such as fonts, can share VM storage in some versions of the PostScript interpreter.
In synthetic fonts, for example, the charstrings of the font may be shared.<BR>
These numbers are not determined in the resource. Rather, they are deter-mined
by the resource creator when the resource (for example, a font) is ini-tially
programmed. The numbers are placed in the resource as static entities in
this comment. To achieve accurate results when determining the usage values,
make sure there are no dependencies on other resources or conditions.<BR>
The VM a resource uses can be found by issuing the vmstatus command before
and after downloading a resource, and then again after downloading the same
resource a second time. The difference between the first and second numbers
(before and after the first downloading) yields the max value; the difference
between the second and third (after the second download) yields the min
value. The following example illustrates how to obtain the max and min values
for a resource:<BR>
<BR>
<div class=code>
<CODE>vmstatus pop /vmstart exch def pop <BR>
...The resource goes here... <BR>
vmstatus pop dup vmstart sub (Max: ) print == flush <BR>
/vmstart exch def pop <BR>
...The resource goes here... <BR>
vmstatus pop vmstart sub (Min: ) print == flush pop</CODE><BR>
</div>
<BR>
Note To obtain accurate memory usage values, it is important to turn off
the garbage collection mechanism in Level 2.<BR>
<HR><A NAME="ReqBody"></A>
<h2>6.2 Requirement Body Comments</h2><BR>
Some of the comments listed in this section, if used, must have a corresponding
comment in the header of the document. For example, if the %%IncludeResource:
comment is used, there must be a %%DocumentNeededResources: comment in the
header of the document.<BR>
<BR>
Table 2 Body and header comment usage<BR>
Body Comment Used Corresponding Header Comment<BR>
%%Begin(End)Document: %%DocumentSuppliedResources: file<BR>
%%IncludeDocument: %%DocumentNeededResources: file<BR>
%%Begin(End)Resource: %%DocumentSuppliedResources:<BR>
%%IncludeResource: %%DocumentNeededResources:<BR>
%%Begin(End)File: %%DocumentSuppliedResources: file<BR>
%%IncludeFile: %%DocumentNeededResources: file<BR>
%%Begin(End)Font: %%DocumentSuppliedResources: font<BR>
%%IncludeFont: %%DocumentNeededResources: font<BR>
%%Begin(End)ProcSet: %%DocumentSuppliedResources: procset<BR>
%%IncludeProcSet: %%DocumentNeededResources: procset<BR>
%%Begin(End)Feature: %%Requirements: or %%DocumentMedia:<BR>
%%IncludeFeature: %%Requirements: or %%DocumentMedia<BR>
<BR>
%%Begin and %%End comments indicate that the PostScript language instructions
enclosed by these comments is a resource, feature, or document. An intelligent
document manager may save resources for future use by creating a resource
library on the host system. The document manager may replace printer-specific
feature instructions when rerouting the document to a different printer,
or may ignore duplicate DSC comments in an included document. The proper
use of these comments facilitates this intelligent document handling.<BR>
%%Include comments indicate that the named resource, feature, or document
(for example, font, procset, file, paper attribute, EPS file, and so on)
should be included in the document at the point where the comment is encountered.
The document manager fulfills these requirements so there is an inherent
risk in using these comments in a document. If there is no document manager
in your system environment, the document may not print correctly. As the
DSC become more prevalent and strictly adhered to, there will be more document
manager products available to take advantage of these %%Include comments.<BR>
<BR>
<A NAME="BeginDocument"></A><B>%%BeginDocument: </B>&lt;name&gt; [ &lt;version&gt;
[ &lt;type&gt; ] ] <BR>
&lt; name&gt; ::= &lt; text&gt; (Document name) <BR>
&lt; version&gt; ::= &lt; real&gt; (Document version) <BR>
&lt; type&gt; ::= &lt; text&gt; (Document type)<BR>
<BR>
<A NAME="EndDocument"></A><B>%%EndDocument </B>(no keywords)<BR>
These comments delimit an entire conforming document that is imported as
part of another PostScript language document or print job. The name of the
document is usually environment-specific; it can be an operating system
file name or a key to a document database. The version and type fields are
optional and, if used, should provide extra information for recognizing
specific documents (an example of usage is a version control system).<BR>
The %%BeginDocument: comment is necessary to allow multiple occurrences
of the %!PS-Adobe-3.0, %%EndProlog, %%Trailer, and %%EOF comments in the
body of a document. Any document file that is embedded within another document
file must be surrounded by these comments.<BR>
Note All feature and resource requirements of an included (child) document
should be inherited by the including (parent) document. For example, if
a child document needs the StoneSerif font resource, this must be reflected
in the %%DocumentNeededResources: comment of the parent. This is neces-sary
so document managers can examine the top level header of any docu-ment and
know all resources and features that are required.<BR>
<BR>
<A NAME="IncludeDocument"></A><B>%%IncludeDocument: </B>&lt; name&gt; [&lt;
version&gt; [&lt; revision&gt;] ] <BR>
&lt; name&gt; ::= &lt; text&gt; (Document name) <BR>
&lt; version&gt; ::= &lt; real&gt; (Version of the document) <BR>
&lt; revision&gt; ::= &lt; int&gt; (Revision of version)<BR>
<BR>
This comment is much like the %%IncludeResource: file comment except that
it specifies that the included file is a conforming document description
rather than a small portion of stand-alone PostScript language code. This
means that, in all probability, the document contains at least one instance
of showpage, and the included document should be wrapped with a save and
restore. In particular, illustrations and EPSF files that have no effect
other than to make marks on a page are perfectly suited for the %%IncludeDocument:
convention.<BR>
When a document file is printed, usually a certain amount of PostScript
lan-guage code is added to the file. Such code may deal with font downloading
issues, paper sizes, or other aspects of printing once a printer has been
selected for the document. At that stage, the printing manager must remove
the %%IncludeDocument: comment and embed the requested document (along with
all the structuring conventions that may fall within that file) between
%%BeginDocument: and %%EndDocument comments.<BR>
<BR>
<A NAME="BeginFeature"></A><B>%%BeginFeature: </B>&lt; featuretype&gt; [
&lt; option&gt; ] <BR>
&lt; featuretype&gt; ::= &lt; text&gt; (PPD feature name) <BR>
&lt; option&gt; ::= &lt; text&gt; (Feature option)<BR>
<BR>
<A NAME="EndFeature"></A><B>%%EndFeature</B> (no keywords)<BR>
The %%BeginFeature and %%EndFeature comments delimit any PostScript language
fragments that invoke a printer-specific feature on a printer. The featuretype
corresponds to one of the keywords in the PostScript printer description
(PPD) file, and the featuretype option sequence must be exactly as it is
found in the PPD file so it cooperates effectively with these conven-tions.<BR>
A document manager may choose to replace the enclosed PostScript language
code with the proper sequence of instructions if the document is sent to
a different printer than originally intended. In a sense, this is the opposite
of the %%IncludeFeature: comment, which indicates that the document manager
must invoke the specified printer feature at that position in the print
file. The next two examples set up an imageable region for a job. Example
4 uses the Level 1 statusdict method of selecting page size. Example 5 uses
the new Level 2 setpagedevice operator.<BR>
<BR>
Example 4<BR>
<div class=code>
<CODE>%%BeginFeature: *PageSize Legal legal <BR>
%%EndFeature</CODE><BR>
</div>
<BR>
Example 5<BR>
<div class=code>
<CODE>%%BeginFeature: *PageSize Legal &lt;&lt; /PageSize [612 1004] &gt;&gt;
setpagedevice <BR>
%%EndFeature<BR>
<BR>
</CODE>
</div>
<A NAME="IncludeFeature"></A><B>%%IncludeFeature: </B>&lt;featuretype&gt;
[ &lt;option&gt; ] <BR>
&lt; featuretype&gt; ::= &lt; text&gt; (Name of desired feature) <BR>
&lt; option&gt; ::= &lt; text&gt; (Feature option)<BR>
<BR>
This comment specifies the need for a particular printer feature, as described
in the PostScript printer description (PPD) file. Its use specifies a requirement
a document manager must fulfill before printing (see also the discussion
under %%BeginFeature). The document file may make the assumption that the
%%IncludeFeature line in the file is replaced by the appropriate PostScript
language fragment from the appropriate PPD file, and that the execution
of the file may be contextually dependent upon this replacement. This offers
a very powerful way of making a document behave differently on different
printers in a device-independent manner. See the PostScript Printer Description
Files Specification for more information about PPD files.<BR>
<BR><A NAME="BeginFile"></A>
<B>%%BeginFile: </B>&lt;filename&gt;<BR>
<B>%%EndFile</B> (no keywords)<BR>
The enclosed segment is a fragment of PostScript language code or some other
type of resource that does not fall within any of the other resource categories.
The file-server component of a document manager may extract a copy of this
file for later use by the %%IncludeFile: or %%IncludeResource: file comments.
The file name will usually correspond to the original disk file name on
the host system.<BR>
<I>Note This comment is provided for backward compatibility and may be discontinued
in later versions of the DSC. Use the more general %%Begin(End)Resource:
comments instead.<BR>
<BR><A NAME="IncludeFile"></A>
</I><B>%%IncludeFile: </B>&lt;filename&gt;<BR>
Indicates that the document manager must insert the specified file at the
cur-rent position in the document. The file name specified also must appear
in the %%DocumentNeededResources: file or the %%DocumentNeededFiles: list.<BR>
<I>Note This comment is provided for backward compatibility and may be discontinued
in later versions of the DSC. Use the more general %%IncludeResource: comment
instead.<BR>
<BR><A NAME="BeginFont"></A>
</I><B>%%BeginFont: </B>&lt;fontname&gt; [ &lt;printername&gt; ] &lt; printername&gt;
::= &lt; text&gt;<BR>
<B>%%EndFont</B> (no keywords)<BR>
These comments delimit a downloaded font. The font-server component of a
document manager may remove the font from the print file (for instance,
if the font is already resident on the chosen printer) or it may simply
keep a copy of it for later use by the %%IncludeFont: or %%IncludeResource:
font comments. The fontname field must be the valid PostScript language
name of the font as used by the definefont operator, and the optional printername
field may contain the network name of the printer, in an environment where
fonts may be tied to particular printers.<BR>
<I>Note This comment is provided for backward compatibility and may be discontinued
in later versions of the DSC. Use the more general %%Begin(End)Resource:
comments instead.<BR>
<BR><A NAME="IncludeFont"></A>
</I><B>%%IncludeFont: </B>&lt;fontname&gt;<BR>
Indicates that the document manager must include the specified font at the
current position in the document. The fontname specified should be the cor-rect
PostScript language name for the font (without the leading slash). Due to
the presence of multiple save/restore contexts, a document manager may have
to supply a specific font more than once in one document, and should do
so whenever this comment is encountered.<BR>
<I>Note This comment is provided for backward compatibility and may be discontinued
in later versions of the DSC. Use the more general %%IncludeResource: comment
instead.<BR>
<BR><A NAME="BeginProcSet"></A>
</I><B>%%BeginProcSet: </B>&lt;procname&gt;<BR>
<B>%%EndProcSet </B>(no keywords)<BR>
The PostScript language instructions enclosed by the %%BeginProcSet: and
%%EndProcSet comments typically represents some subset of the document prolog.
The prolog may be broken down into many subpackages, or proce-dure sets
(procsets), which may define groups of routines appropriate for different
imaging requirements. These individual procsets are identified by name,
version, and revision numbers for reference by a document manage-ment system.
A document manager may choose to extract these procsets from the print file
to manage them separately for a whole family of documents. An entire document
prolog may be an instance of a procset, in that it is a body of procedure
definitions used by a document description file. (See the %%DocumentProcSets:,
%%IncludeProcSet:, and %%IncludeResource: procset comments). The name, version,
and revision fields should uniquely identify the procset. The name may consist
of a disk file name or it may use a PostScript language name under which
the prolog is stored in the printer. See the %%?Begin(End)ProcSetQuery:
and the %%?Begin(End)ResourceQuery: procset comment, which one may use to
query the printer or document manager for the prolog name and version fields.<BR>
A document manager may assume that the document prolog consists of everything
from the beginning of the print file through the %%EndProlog comment, which
may encompass several instances of the %Begin(End)ProcSet: comments.<BR>
<I>Note This comment is provided for backward compatibility and may be discontinued
in later versions of the DSC. Use the more general %%Begin(End)Resource:
comments instead.<BR>
<BR><A NAME="IncludeProcSet"></A>
</I><B>%%IncludeProcSet: </B>&lt;procname&gt;<BR>
This is a special case of the more general %%IncludeResource: file comment.
It requires that a PostScript language procset with the given name, version,
and revision be inserted into the document at the current position. If a
version-numbering scheme is not used, these fields should still be filled
with a &quot;dummy&quot; value, such as 0. See the %%Begin(End)Resource:
and %DocumentNeededResources: comments.<BR>
<I>Note This comment is provided for backward compatibility and may be discontinued
in later versions of the DSC. Use the more general %%IncludeResource: comment
instead.<BR>
<BR><A NAME="BeginResource"></A>
</I><B>%%BeginResource: </B>&lt;resource&gt; [&lt;max&gt; &lt;min&gt;] <BR>
&lt; max&gt; ::= &lt; uint&gt; (Maximum VM used by resource) <BR>
&lt; min&gt; ::= &lt; uint&gt; (Minimum VM used by resource)<BR>
<BR><A NAME="EndResource"></A>
<B>%%EndResource</B> (no keywords)<BR>
These comments delimit a resource that is defined by PostScript language
code directly in the document file-for example, downloadable fonts. The
resource-management component of the document manager may remove the resource
from the print file and replace it with an %%IncludeResource comment (for
instance, if the chosen printer already has the resource resident) or it
may simply keep a copy of it for later use by the %%IncludeResource: comment.
The resource name specified should also appear in the %%DocumentSuppliedResources:
list.<BR>
The optional usage parameters should be supplied if the %%VMusage: comment
is not provided in the resource. A document manager can use these numbers
to determine if a particular resource will fit inside the printer VM. If
it cannot, the document manager may move the resource within the print file,
juggling resources until the file can fit, or it may reroute the print file
to a printer with more VM. See the %%VMusage: comment for details on how
to obtain these numbers for a resource.<BR>
Font note-These comments delimit a font that is being downloaded. The font
server component of a document manager may remove the font from the print
file (for instance, if the chosen printer already has the font resident)
or it may simply keep a copy of it for later use by the %%IncludeResource:
comment.<BR>
File note-The enclosed segment is a fragment of PostScript language code
or some other item that does not fall within the other resource categories.
The file-server component of the document manager may extract a copy of
this file for later use by the %%IncludeResource: comment. The file name
will usually correspond to the original disk file name on the host system.<BR>
<BR>
Procset note-The PostScript language code enclosed by these comments typically
represents some subset of the document prolog. The prolog may be broken
down into many procedure sets, which may define groups of routines appropriate
for different imaging requirements. These individual procsets are identified
by a name, version, and optional revision numbers for reference by a print
management system. A document manager may choose to extract these procsets
from a print file to manage them separately for a whole family of documents.
An entire document prolog may be an instance of a procset, in that it is
a body of procedure definitions used by a document description file.<BR>
<BR><A NAME="IncludeResource"></A>
<B>%%IncludeResource: </B>&lt;resource&gt;<BR>
Indicates that the document manager must include the named resource at this
point in the document. The resource name specified also must appear in the
%%DocumentNeededResources: list. It is up to the application creating the
document to manage memory for resources that employ this comment (using
save/restore pairs). Although the font example below is specific to fonts,
memory management and resource optimization are also applicable to forms,
patterns, and other memory-intensive resources.<BR>
Font note-In the case of commonly available fonts, it is highly likely that
the font server or document manager would ignore the inclusion request,
because the fonts would already be available on the printer. However, the
%%IncludeResource: font comment must still be included so that if a stan-dard
font is not available it can be supplied (there are printers that do not
have the 13 standard fonts that are resident in most of Adobe's PostScript
implementations). %%IncludeResource: font comments of this nature should
be placed in the document setup section.<BR>
<BR>
Due to the presence of multiple save/restore contexts, a font server may
have to supply a specific font more than once within a single document,
and should do so whenever this comment is encountered. Depending on the
memory available in the target printer, a document manager may optimize
font usage by moving the inclusion of fonts within the document. A frequently
used font could be downloaded during the document setup, thus making it
available for use by any page. A font that is used on one or two particular
pages, could be downloaded during the page setups for each of the individual
pages. A special font that is used for one or two paragraphs on one page
only would not be moved.<BR>
<BR>
In Example 6, four different fonts (ITC Stone &reg; , Palatino*, Carta &reg;
, and Sonata &reg; ) are downloaded. The memory management scheme used by
the application that generated this code assumes that up to three fonts
may be downloaded at any one point in time. Note the use of multiple %%IncludeResource:
font comments for the same font when a save-restore pair &quot;undefines&quot;
previously included fonts.<BR>
<BR>
Example 6<BR>
%!PS-Adobe-3.0 <BR>
%%Title: (Example of memory management) <BR>
%%DocumentNeededResources: font Helvetica Helvetica-Bold <BR>
%%+ font StoneSerif Palatino-Roman Carta Sonata <BR>
%%EndComments <BR>
%%BeginDefaults <BR>
%%PageResources: font Helvetica Helvetica-Bold StoneSerif <BR>
%%EndDefaults <BR>
%%BeginProlog <BR>
...Document prolog... <BR>
%%EndProlog <BR>
%%BeginSetup <BR>
% Include the common fonts found in most implementations <BR>
%%IncludeResource: font Helvetica <BR>
%%IncludeResource: font Helvetica-Bold <BR>
...Rest of the set up... <BR>
%%EndSetup <BR>
%%Page: 1 1 %%PageResources: font Helvetica Helvetica-Bold <BR>
%%+ font StoneSerif Palatino-Roman Carta Sonata <BR>
%%BeginPageSetup /pagelevel save def <BR>
%%EndPageSetup <BR>
...Text that uses common fonts like Helvetica... <BR>
/fontlevel save def <BR>
%%IncludeResource: font StoneSerif <BR>
...Text that uses the StoneSerif font and/or common fonts... <BR>
%%IncludeResource: font Palatino-Roman <BR>
...Text that uses Palatino-Roman, StoneSerif and/or common fonts... <BR>
%%IncludeResource: font Carta <BR>
...Text that uses the Carta, Palatino-Roman, StoneSerif, and/or common fonts...
<BR>
fontlevel restore <BR>
% Ran out of room for new fonts /fontlevel save def <BR>
%%IncludeResource: font StoneSerif <BR>
%%IncludeResource: font Palatino-Roman <BR>
%%IncludeResource: font Sonata <BR>
...Text that uses the Sonata, Palatino-Roman, StoneSerif, and/or common
fonts... <BR>
fontlevel restore <BR>
% Need to switch fonts /fontlevel save def <BR>
%%IncludeResource: font StoneSerif <BR>
%%IncludeResource: font Carta <BR>
...Text that uses the Carta, StoneSerif, and/or common fonts... <BR>
pagelevel restore showpage <BR>
%%Page: 2 2 <BR>
%%PageResources: font StoneSerif Palatino-Roman <BR>
...Rest of the document... <BR>
%%EOF<BR>
<BR>
At print time, the document manager decides there is enough memory available
in the VM of the target device to hold four fonts at any one point in time
and decides to optimize the document. The Helvetica and Helvetica-Bold inclusions
are ignored because these fonts are available on the printer. The page level
comment %%PageResources: font StoneSerif is recognized in the defaults section,
indicating that the font StoneSerif is likely to be used on every page.
The document manager moves the inclusion of this font to the end of the
document setup and ignores all subsequent inclusion requests for StoneSerif.<BR>
The document manager also realizes that the Palatino-Roman font is only
used on pages 1 and 2. This font is downloaded at the end of the page setup
for each page. The Carta and Sonata fonts are used on page 1 only. However,
the Carta font is downloaded twice due to the three-font memory management
scheme used by the application. The document manager also moves the downloading
of the Carta font to the end of the page setup. The Sonata font is used
only once and is downloaded at the %%IncludeResource: font comment. Example
7 shows the resulting file:<BR>
Example 7<BR>
<BR>
%!PS-Adobe-3.0 <BR>
%%Title: (Optimized file) <BR>
%%DocumentNeededResources: font Helvetica Helvetica-Bold <BR>
%%DocumentSuppliedResources: font StoneSerif Palatino-Roman Carta Sonata
<BR>
%%EndComments <BR>
%%BeginDefaults <BR>
%%PageResources: font Helvetica Helvetica-Bold StoneSerif <BR>
%%EndDefaults <BR>
%%BeginProlog <BR>
...Document prolog... <BR>
%%EndProlog <BR>
%%BeginSetup <BR>
% Include the common fonts found in most implementations <BR>
%%IncludeResource: font Helvetica <BR>
%%IncludeResource: font Helvetica-Bold <BR>
%%BeginResource: font StoneSerif <BR>
...StoneSerif font is downloaded here... <BR>
%%EndResource <BR>
...Rest of the set up... <BR>
%%EndSetup <BR>
%%Page: 1 1 <BR>
%%PageResources: font Helvetica Helvetica-Bold <BR>
%%+ font StoneSerif Palatino-Roman Carta Sonata <BR>
%%BeginPageSetup /pagelevel save def <BR>
%%BeginResource: font Palatino-Roman <BR>
...Palatino-Roman font is downloaded here... <BR>
%%EndResource <BR>
%%BeginResource: font Carta<BR>
...Carta font is downloaded here...<BR>
%%EndResource <BR>
%%EndPageSetup <BR>
...Text that uses common fonts like Helvetica... <BR>
/fontlevel save def <BR>
...Text that uses the StoneSerif font and/or common fonts... <BR>
...Text that uses Palatino-Roman, StoneSerif and/or common fonts... <BR>
...Text that uses the Carta, Palatino-Roman, StoneSerif, and/or common fonts...
<BR>
fontlevel restore<BR>
% Ran out of room for new fonts <BR>
/fontlevel save def <BR>
%%BeginResource: font Sonata <BR>
...Sonata font is downloaded here... <BR>
%%EndResource <BR>
...Text that uses the Sonata, Palatino-Roman, StoneSerif, and/or common
fonts... <BR>
fontlevel restore <BR>
% Need to switch fonts again <BR>
/fontlevel save def <BR>
...Text that uses the Carta, StoneSerif, and/or common fonts... <BR>
pagelevel restore showpage <BR>
%%Page: 2 2 <BR>
%%PageResources: font StoneSerif Palatino-Roman <BR>
%%BeginPageSetup <BR>
/pagelevel save def <BR>
%%BeginResource: font Palatino-Roman <BR>
...Palatino-Roman font is downloaded again here... <BR>
%%EndResource <BR>
<BR>
Procset note-The %%IncludeResource: procset comment must appear in the document
prolog only. Procsets do not generally have to worry about save/restore
pairs as in the above example. In the case of procsets, the docu-ment manager
may replace the desired procset with an upwardly compatible version of the
desired procset (a newer version). See section 4.6, &quot;Comment Syntax
Reference,&quot; for more details on compatible procsets. In addition, the
document manager may optimize procset inclusion by replacing a procset that
occurs multiple times with a single copy at the top level of a document.
Example 8 shows the use of the %%IncludeResource: procset comment:<BR>
Example 8<BR>
<BR>
%!PS-Adobe-3.0 <BR>
%%Creator: Adobe Illustrator 88(TM) 1.9.3 <BR>
%%For: (Joe Smith) (Adobe Systems Incorporated) <BR>
%%Title: (Example.art) <BR>
%%CreationDate: (2/08/90) (8:30 am) <BR>
%%DocumentNeededResources: procset Adobe_packedarray 0 0 <BR>
%%+ procset Adobe_cmykcolor 0 0 Adobe_cshow 0 0 Adobe_customcolor 0 0 <BR>
%%+ procset Adobe_Illustrator881 0 0 <BR>
%%+ font StoneSerif <BR>
%%EndComments <BR>
%%BeginProlog <BR>
%%IncludeResource: procset Adobe_packedarray 0 0 <BR>
%%IncludeResource: procset Adobe_cmykcolor 0 0 <BR>
%%IncludeResource: procset Adobe_cshow 0 0 <BR>
%%IncludeResource: procset Adobe_customcolor 0 0 <BR>
%%IncludeResource: procset Adobe_Illustrator881 0 0 <BR>
%%EndProlog <BR>
...Rest of the document... <BR>
%%EOF<BR><A NAME="ReqPage"></A>
<HR>
<h2>6.3 Requirement Page Comments</h2><BR>
Some of the following comments that request particular page media, require-ments,
or resources may appear in the defaults section or in a particular page.
If these comments fall within the defaults section of the document file
(%%BeginDefaults to %%EndDefaults), they may be construed to be in effect
for the entire print job. If they are found within the page-level comments
for a page, they should only be in effect for that page. See %%Begin(End)Defaults
for more details on page defaults.<BR>
<BR><A NAME="PageFonts"></A>
<B>%%PageFonts: </B>{
 <p>

 
 &lt;fontname&gt; ... } | (atend)<BR>
Indicates the names of all fonts used on the current page. The notation
(atend) is permissible. In that case, the list of fonts must be provided
after the %%PageTrailer comment. Also see the %%DocumentFonts: comment.<BR>
Note This comment is provided for backward compatibility and may be discontinued
in later versions of the DSC. Use the more general %%PageResources: comment
instead.<BR>
<BR><A NAME="PageFiles"></A>
<B>%%PageFiles:</B> {
 <p>

 
 &lt;filename&gt; ... } | (atend)<BR>
Indicates the names of all files used on the current page. This should be
used only if file inclusion is required of the document manager-that is,
if there are subsequent instances of the %%IncludeFile: comment on that
particular page. See also %%DocumentNeededFiles: and %%DocumentSuppliedFiles:
comments.<BR>
Note This comment is provided for backward compatibility and may be discontinued
in later versions of the DSC. Use the more general %%PageResources: comment
instead.<BR>
<BR><A NAME="PageMedia"></A>
<B>%%PageMedia: </B>&lt;medianame&gt; <BR>
&lt; medianame&gt; ::= &lt; text&gt; (Name of desired paper media)<BR>
Indicates that the paper attributes denoted by medianame are invoked on
this page. The medianame is specified by the %%DocumentMedia: comment at
the beginning of the document. This comment can pertain to either a page
or a document depending on the position of the comment (for example, either
in the page itself or in the defaults section). See also the %%DocumentMedia:
and %%Begin(End)Defaults comments.<BR>
In Example 9, a one-hundred page report is printed on regular white and
heavy yellow paper. Ninety-nine of the pages use the white paper so the
%%PageMedia: comment is found in the defaults section, denoting that the
default media for this document is white paper. The white paper is set using
the setpagedevice operator in the document setup. The cover page is the
only page to use the yellow paper, and states so via the %%PageMedia:<BR>
comment that appears after the first %%Page: comment. Note the use of the
currentpagedevice operator to facilitate the restoration of the white-paper
device after the cover page.<BR>
<A NAME="Example9"></A>Example 9<BR>
<BR>
%!PS-Adobe-3.0 <BR>
%%Title: (Example of %%PageMedia: as a page default) <BR>
%%DocumentMedia: Regular 612 792 75 white ( ) <BR>
%%+ Cover 612 792 244 yellow DeptLetterHead <BR>
%%Pages: 100 %%LanguageLevel: 2 <BR>
%%EndComments %%BeginDefaults <BR>
%%PageMedia: Regular %%EndDefaults <BR>
%%BeginProlog ...Prolog definitions... <BR>
%%EndProlog <BR>
%%BeginSetup <BR>
&lt;&lt; % Attribute tray numbers to /InputAttributes &lt;&lt; <BR>
% the particular media 0 <BR>
&lt;&lt; /PageSize [612 792] <BR>
/MediaWeight 75 /MediaColor (white) &gt;&gt; 1 <BR>
&lt;&lt; /PageSize [612 792] <BR>
/MediaWeight 244 /MediaColor (yellow) <BR>
/MediaType (DeptLetterHead) &gt;&gt; &gt;&gt; &gt;&gt; setpagedevice<BR>
&lt;&lt; /MediaColor (white) &gt;&gt; setpagedevice % Set the white paper
to be the default for the document <BR>
%%EndSetup <BR>
%%Page: Cover 1 <BR>
%%PageMedia: Cover <BR>
%%BeginPageSetup <BR>
/olddevice currentpagedevice def <BR>
&lt;&lt; /MediaColor (yellow) &gt;&gt; setpagedevice % Set up the yellow
paper for this page <BR>
/pagelevel save def<BR>
%%EndPageSetup <BR>
...Mark the cover page... <BR>
pagelevel restore showpage <BR>
%%PageTrailer <BR>
olddevice setpagedevice % Restore the white paper <BR>
%%Page: 1 2 <BR>
...Rest of the document... % No %%PageMedia: <BR>
%%EOF % comment, white letter paper is the default<BR>
<BR><A NAME="PageRequirements"></A>
%%PageRequirements: &lt;requirement&gt; [(&lt;style&gt;)] ... &lt; requirement&gt;
::= collate | color | duplex | faceup | fax | fold | jog | manualfeed |
numcopies | punch | resolution | rollfed | staple &lt; style&gt; ::= &lt;
text&gt;<BR>
This is the page-level invocation of a combination of the options listed
in the %%Requirements: comment. It takes precedence over any document requirements
set during the document setup. This comment can pertain to a page or a document
depending on the position of the comment (either in the page itself or in
the defaults section). See the %%Requirements: and %%Begin(End)Defaults
comments.<BR>
<BR><A NAME="PageResources"></A>
<B>%%PageResources: </B>{
 <p>

 
 &lt;resource&gt; ... } | (atend)<BR>
This comment indicates the names and values of all resources that are needed
or supplied on the present page (procsets are an exception; they need not
be listed). This comment can pertain to an individual page or a document,
depending on the location of the comment. For example, the comment may be
in the page itself or in the document defaults section. See the %%DocumentSuppliedResources:,
%%DocumentNeededResources:, and %%Begin(End)Defaults comments.<BR>
<A NAME="Separation"></A><HR>
<h2>7 Color Separation Conventions</h2><BR>
Level 2 implementations and Level 1 implementations that contain the CMYK
color extensions to the PostScript language provide more complete color
functionality than the RGB color model in Level 1. There are corre-sponding
color separation comments that programs producing PostScript language documents
with color operators should use. Color separation applications can use these
comments as an aid in proper color determination and to identify process
color specific portions of PostScript language code. These comments can
also be used to enable applications to communicate spot color usage.<BR>
Note These comments do not address the use of CIE based and special color
spaces. Expect future versions of the DSC to do so.<BR>
<A NAME="ColorHeader"></A><HR>
<h2>7.1 Color Header Comments</h2><BR>
<BR><A NAME="CMYKCustomColor"></A>
<B>%%CMYKCustomColor: </B>&lt;cya&gt; &lt;mag&gt; &lt;yel&gt; &lt;blk&gt; &lt;colorname&gt;
<BR>
&lt; cya&gt; :: = &lt; real&gt; (Cyan percentage) <BR>
&lt; mag&gt; ::= &lt; real&gt; (Magenta percentage) <BR>
&lt; yel&gt; ::= &lt; real&gt; (Yellow percentage) <BR>
&lt; blk&gt; ::= &lt; real&gt; (Black percentage) <BR>
&lt; colorname&gt; ::= &lt; text&gt; (Custom color name)<BR>
This comment provides an approximation of the custom color specified by
colorname. The four components of cyan, magenta, yellow, and black must
be specified as numbers from 0 to 1 representing the percentage of that
process color. The numbers are similar to the arguments to the setcmykcolor
operator. The colorname follows the same custom color naming conventions
as the %%DocumentCustomColors: comment.<BR>
<BR><A NAME="DocumentCustomColors"></A>
<B>%%DocumentCustomColors: </B>{
 <p>

 
 &lt;colorname&gt; ... } | (atend) <BR>
&lt; colorname&gt; ::= &lt; text&gt; (Custom color name)<BR>
This comment indicates the use of custom colors in a document. An applica-tion
arbitrarily names these colors, and their CMYK or RGB approximations are
provided through the %%CMYKCustomColor: or %%RGBCustomColor: comments in
the body of the document. Normally, the colorname specified can be any arbitrary
string except Cyan, Magenta, Yellow, or Black. If imaging to a specific
process layer is desired, these names may be used.<BR>
<BR><A NAME="DocumentProcessColors"></A>
<B>%%DocumentProcessColors: </B>{
 <p>

 
 &lt;color&gt; ... } | (atend) <BR>
&lt; color&gt; ::= Cyan | Magenta | Yellow | Black<BR>
This comment marks the use of process colors in the document. Process colors
are defined to be Cyan, Magenta, Yellow, and Black. This comment is used
primarily when producing color separations. See also %%PageProcessColors:.<BR>
<BR><A NAME="RGBCustomColor"></A>
<B>%%RGBCustomColor: </B>&lt;red&gt; &lt;green&gt; &lt;blue&gt; <BR>
&lt;colorname&gt; &lt; red&gt; ::= &lt; real&gt; <BR>
(Red percentage) &lt; green&gt; ::= &lt; real&gt; <BR>
(Green percentage) &lt; blue&gt; ::= &lt; real&gt; <BR>
(Blue percentage) <BR>
&lt; colorname&gt; ::= &lt; text&gt; (Custom color name)<BR>
This comment provides an approximation of the custom color specified by
colorname. The three components of red, green, and blue must be specified
as numbers from 0 to 1 representing the percentage of that process color.
The numbers are similar to the arguments to the setrgbcolor operator. The
colorname follows the same custom color naming conventions as the %%DocumentCustomColors:
comments<A NAME="ColorBody"></A><HR><HR>
<h2>7.2 Color Body Comments</h2><BR>
<BR><A NAME="BeginCustomColor"></A>
<B>%%BeginCustomColor: </B>&lt;colorname&gt; <BR>
&lt; colorname&gt; ::= &lt; text&gt; (Custom color name)<BR>
<BR><A NAME="EndCustomColor"></A>
<B>%%EndCustomColor </B>(no keywords)<BR>
These comments specify that the PostScript language code fragment enclosed
within should be interpreted only when rendering the separation identified
by colorname. The colorname here is any text string except Cyan, Magenta,
Yellow, and Black (see the exception in %%DocumentCustomColors:). During
color separation, the code between these comments must only be downloaded
during the appropriate pass for that custom color. Intelligent printing
managers can save considerable time by omitting code within these bracketing
comments during any other separations. The document composi-tion software
must be extremely careful to correctly control overprinting and knockouts
if these comments are employed, because the enclosed code may or may not
be executed.<BR>
Note In the absence of a document manager that understands these comments,
the document will print incorrectly. These comments should be used only
if the environment supports such a document manager.<BR>
<BR><A NAME="BeginProcessColor"></A>
<B>%%BeginProcessColor: </B>&lt;color&gt; &lt; color&gt; ::= Cyan | Magenta
| Yellow | Black<BR>
<BR><A NAME="EndProcessColor"></A>
<B>%%EndProcessColor</B> (no keywords)<BR>
These comments specify that the PostScript language code fragment enclosed
within should be interpreted only when rendering the separation identified
by color. During color separation, the code between these comments must
be downloaded only during the appropriate pass for that process color. Intelligent
printing managers can save considerable time by omitting code within these
bracketing comments on the other three separations. The document composi-tion
software must be extremely careful to correctly control overprinting and
knockouts if these comments are employed, because the code may or may not
be executed.<BR>
Note In the absence of a document manager that understands these comments,
the document will print incorrectly. These comments should only be used
if the environment supports such a document manager.<BR>
<A NAME="ColorPage"></A><HR>
<h2>7.3 Color Page Comments</h2><BR>
<BR><A NAME="PageCustomColors"></A>
<B>%%PageCustomColors: </B>{
 <p>

 
 &lt;colorname&gt; ... } | (atend) <BR>
&lt; colorname&gt; ::= &lt; text&gt; (Custom color name)<BR>
This comment indicates the use of custom colors in the page. An application
arbitrarily names these colors, and their CMYK or RGB approximations are
provided through the %%CMYKCustomColor: or %%RGBCustomColor: comments in
the body of the document. See the %%DocumentCustomColors: comment.<BR>
<BR><A NAME="PageProcessColors"></A>
<B>%%PageProcessColors: </B>{
 <p>

 
 &lt;color&gt; ... } | (atend) <BR>
&lt; color&gt; ::= Cyan | Magenta | Yellow | Black<BR>
This comment marks the use of process colors in the page. Process colors
are defined as Cyan, Magenta, Yellow, and Black. See the %%DocumentProcessColors:
comment.<BR>
<A NAME="Query"></A><HR>
<h2>8 Query Conventions</h2><BR>
<BR>
A query is any PostScript language program segment that generates and returns
information back to the host computer across the communications channel
before a document can be formatted for printing. This might result from
the execution of any of the =, ==, print or pstack operators, for instance.
In particular, this definition covers information that is expected back
from the PostScript printer for decision-making purposes. Such decision-making
might include the generation of font lists or inquiries about the availability
of resources, printer features, or the like.<BR>
All query conventions consist of a begin and end construct, with the keywords
reflecting the type of query. For all of them, the %%?EndQuery comment should
include a field for a default value, which document managers must return
if they cannot understand or do not support query comments. The value of
the default is entirely application dependent, and an application can use
it to determine specific information about the spooling environment, if
any, and to take appropriate default action.<BR>
<A NAME="Responsibilities"></A><HR>
<h2>8.1 Responsibilities</h2><BR>
<BR>
A document manager that expects to be able to interpret and correctly spool
documents conforming to DSC version 3.0 must, at a minimum, perform cer-tain
tasks in response to these query conventions. In general, it must recog-nize
the queries, remove them from the print stream, and send some reply back
to the host. If a document manager cannot interpret the query, it must return
the value provided as the argument to the %%?EndQuery comment.<BR>
A query can be recognized by the sequence %%?Begin followed by any number
of characters (up to the 255 maximum per line, by convention) through the
end-of-line indication (the % is decimal ASCII 37, and the ? is decimal
ASCII 63). The end of the query is delimited by the sequence %%?End followed
by some keywords, and optionally followed by a colon (: is decimal ASCII
58) and the default response to the query (any text through end-of-line).
A document manager should try to recognize the full query keyword, such
as %%?BeginResourceQuery:, if it can, but it is obligated at least to respond
to any validly formed query.<BR>
If a more intelligent query handling interface is desired, the document
manager must recognize which printer the application is printing to (the
%%DocumentPrinterRequired: comment may be helpful in this case). By using
the PPD file for that particular printer, the known printer network configuration,
and the printer status, the document manager should be able to answer the
query.<BR>
<A NAME="QueryComments"></A><HR>
<h2>8.2 Query Comments</h2><BR>
<BR>
%!PS-Adobe-3.0 Query (no keywords)<BR>
A PostScript language query must be sent as a separate job to the printer
to be fully spoolable. This means that an end-of-file indication must be
sent immediately after the query job. A query job must always begin with
the %!PS-Adobe-3.0 Query convention, which further qualifies the file as
being a special case of a version 3.0 conforming PostScript language file.
A query job contains only query comments, and need not contain any of the
other standard structuring conventions. A document manager must be prepared
to extract query information from any print file that begins with this comment
convention. A document manager must fully parse a query job file until the
EOF indication is reached.<BR>
Note It is permissible to include more than one query in a print job, but
it is not permissible to include queries within the body of a regular print
job. It cannot be guaranteed that a document manager can properly handle
a print job with embedded queries.<BR>
<BR><A NAME="BeginFeatureQuery"></A>
<B>%%?BeginFeatureQuery: </B>&lt; featuretype&gt; [ &lt; option&gt; ] <BR>
&lt; featuretype&gt; ::= &lt; text&gt; (Requested feature) <BR>
&lt; option&gt; ::= &lt; text&gt; (Feature option)<BR>
<BR><A NAME="EndFeatureQuery"></A>
<B>%%?EndFeatureQuery: </B>&lt; default&gt; <BR>
&lt; default&gt; ::= &lt; text&gt; (Default response)<BR>
This query provides information that describes the state of some specified,
printer-specific feature as defined by the PostScript printer description
(PPD) file. The featuretype field identifies the keyword as found in the
PPD file. The standard response varies with the feature and is defined by
the printer's PPD file. In general, the value of the &lt; featuretype&gt;
or the value of &lt; option&gt; associ-ated with the feature should be returned.
In the example that follows, the PPD file keywords True or False are returned:<BR>
%%?BeginFeatureQuery: *InputSlot manualfeed <BR>
statusdict /manualfeed known {
 <p>

 
 <BR>
statusdict /manualfeed get {
 <p>

 
 (True) }{
 <p>

 
 (False) } ifelse <BR>
}{
 <p>

 
 <BR>
(None) } ifelse = flush <BR>
%%?EndFeatureQuery: Unknown<BR>
<BR><A NAME="BeginFileQuery"></A>
%%?BeginFileQuery: &lt; filename&gt;<BR>
<BR><A NAME="EndFileQuery"></A>
%%?EndFileQuery: &lt; default&gt; <BR>
&lt; default&gt; ::= &lt; text&gt; (Default response)<BR>
The PostScript language code between these comments causes the printer to
respond with information describing the availability of the specified file.
This presumes the existence of a file system that is available to the PostScript
interpreter, which is not the case on all implementations. The standard
response consists of a line containing the file name, a colon, and either
Yes or No, indicating whether the file is present.<BR>
Note This comment is provided for backward compatibility and may be discontinued
in later versions of the DSC. Use the more general %%?Begin(End)ResourceQuery:
comments instead.<BR>
<BR><A NAME="BeginFontListQuery"></A>
<B>%%?BeginFontListQuery </B>(no keywords)<BR>
<BR><A NAME="EndFontListQuery"></A>
<B>%%?EndFontListQuery: </B>&lt; default&gt; <BR>
&lt; default&gt; ::= &lt; text&gt; (Default response)<BR>
Provides a PostScript language sequence to return a list of all available
fonts. It should consult the FontDirectory dictionary and any mass storage
devices available to the interpreter. The list need not be in any particular
order, but each name should be returned separated by a slash / character.
This is nor-mally the way the PostScript == operator returns a font name.
All white space characters should be ignored. The end of the font list must
be indicated by a trailing * (asterisk) sign on a line by itself (decimal
ASCII 42).<BR>
Note This comment is provided for backward compatibility and may be discontinued
in later versions of the DSC. Use the more general %%?Begin(End)ResourceListQuery:
comments instead.<BR>
<BR><A NAME="BeginFontQuery"></A>
%%?BeginFontQuery: &lt; fontname&gt; ...<BR>
<BR><A NAME="EndFontQuery"></A>
%%?EndFontQuery: &lt; default&gt; <BR>
&lt; default&gt; ::= &lt; text&gt; (Default response)<BR>
This comment provides a PostScript language query that should be combined
with a particular list of font names being sought. It looks for any number
of names on the stack and prints a list of values depending on whether the
font is known to the PostScript interpreter. The font names must be provided
on the operand stack by the document manager. This is done by simply sending
the names, with leading slash / characters, before sending the query itself.<BR>
To prevent the document manager from having to keep track of the precise
order in which the values are returned and to guard against errors from
dropped information, the syntax of the returned value /FontName:Yes or /
FontName:No, with no space between the colon and the following word.<BR>
Each font in the list is returned this way. The slashes delimit the individually
returned font names, although newlines should be expected (and ignored)
between them. A final * (asterisk) character follows the returned values.<BR>
Note This comment is provided for backward compatibility and may be discontinued
in later versions of the DSC. Use the more general %%?Begin(End)ResourceQuery:
comments instead.<BR>
<BR><A NAME="BeginPrinterQuery"></A>
<B>%%?BeginPrinterQuery</B> (no keywords)<BR>
<BR><A NAME="EndPrinterQuery"></A>
<B>%%?EndPrinterQuery: </B>&lt; default&gt; &lt; default&gt; ::= &lt; text&gt;
(Default response)<BR>
This comment delimits PostScript language code that returns information
describing the printer's product name, version, and revision numbers. The
standard response consists of the printer's product name, version, and revi-sion
strings, each of which must be followed by a newline character, which must
match the information in the printer's printer description file. This comment
may also be used to identify the presence of a spooler, if necessary. In
the following example the default response as represented in the %%?EndPrinterQuery:
line is the word spooler, which would be returned by spooling software that
did not have a specific printer type attached to it.<BR>
<BR>
%%?BeginPrinterQuery <BR>
statusdict begin <BR>
revision == version == productname == flush <BR>
end <BR>
%%?EndPrinterQuery: spooler<BR>
<BR><A NAME="BeginProcSetQuery"></A>
<B>%%?BeginProcSetQuery: </B>&lt; procname&gt;<BR>
<BR><A NAME="EndProcSetQuery"></A>
<B>%%?EndProcSetQuery: </B>&lt; default&gt; <BR>
&lt; default&gt; ::= &lt; text&gt; (Default response)<BR>
These comments delimit a procset query. The combination of the name, version,
and revision fields must uniquely identify the procset. The standard response
to this query consists of a line containing any of the values 0, 1, 2 where
a value of 0 means the procset is missing, a value of 1 means the procset
is present and OK, and a value of 2 indicates the procset is present but
is an incompatible version. Note that methods for procset queries are procset
specific.<BR>
<BR>
%%?BeginProcSetQuery: adobe_distill 1.1 1 <BR>
/adobe_distill_dict where <BR>
{
 <p>

 
 begin mark VERSION (1.) anchorsearch {
 <p>

 
(1)}{
 <p>

 
(2)} ifelse cleartomark end
}<BR>
{
 <p>

 
 (0) } ifelse print flush <BR>
%%?EndProcSetQuery: unknown<BR>
<BR>
Note This comment is provided for backward compatibility and may be discontinued
in later versions of the DSC. Use the more general %%?Begin(End)ResourceQuery:
comments instead.<BR>
<BR><A NAME="BeginQuery"></A>
<B>%%?BeginQuery: </B>&lt; identifier&gt; <BR>
&lt; identifier&gt; ::= &lt; text&gt; (Query identifier)<BR>
<BR><A NAME="EndQuery"></A>
<B>%%?EndQuery: </B>&lt; default&gt; <BR>
&lt; default&gt; ::= &lt; text&gt; (Default response)<BR>
These comments are for very general purposes and may serve any function
that the rest of the query conventions, which are very specific, do not
adequately cover. To understand and intelligently respond to a query, a
document manager must semantically understand the query. Therefore, specific
keywords, such as %%?BeginPrinterQuery, are used. When the generic %%?BeginQuery
comment is encountered, a spooler may be forced to return the default value.
The comment is included primarily for large installations that must implement
specific additional queries not covered here, and which will likely implement
the document composition software and the document manager software.<BR>
<BR><A NAME="BeginResourceListQuery"></A>
<B>%%?BeginResourceListQuery:</B> font | file | procset | pattern | form
| encoding<BR>
<BR><A NAME="EndResourceListQuery"></A>
<B>%%EndResourceListQuery: </B>&lt; text&gt;<BR>
These comments delimit a segment of PostScript language code that returns
a list of all available resources. The arguments specify which type of resources
to return. The code that these comments delimit should consult local VM,<BR>
global VM, and any mass storage devices available to compile a complete
list of resources. The resulting list need not be in any particular order,
but the syntax of the returned values is the resource type followed by the
resource name. The end of the resource list must be indicated by a trailing
* (asterisk) on a line by itself.<BR>
Note that font names must be returned with a slash / character in front
of each font name.<BR>
Note The use of this type of query is discouraged because it can be time
consuming for interpreters with many accessible resources (for example,
a printer with a hard disk attached). It is far better to query for individual
resources by using the %%?Begin(End)ResourceQuery: comment.<BR>
<BR>
<B>%%?BeginResourceQuery: </B>&lt;resource&gt;...<BR>
<BR>
<B>%%?EndResourceQuery: </B>&lt;default&gt; <BR>
&lt; default&gt; ::= &lt; text&gt; (Default response)<BR>
The PostScript language code between these comments causes the printer to
respond with information describing the availability of the specified resources.
This code looks for any number of resource names on the stack, and prints
a list of values depending on whether the resource is known to the PostScript
interpreter.<BR>
The document manager could also process this query by using information
known about the print network and current printer status. To reduce the
overhead involved in keeping track of the precise order in which values
are returned, and to guard against errors from dropped information, the
syntax of the returned value is the resource type and name followed by a
colon, a space and then a yes or a no. The end of the list should be denoted
by a *.<BR>
<BR>
Note It is recommended that a separate resource query be used for each type
of resource.<BR>
<BR>
A file resource query presumes that a file system is available to the PostScript
interpreter. This is not the case in all implementations. Example 10 shows
a typical font resource query:<BR>
Example 10<BR>
<BR>
<div class=code><CODE>
%!PS-Adobe-3.0 Query <BR>
%%Title: (Resource query for specified fonts) <BR>
%%?BeginResourceQuery: font Times-Roman Adobe-Garamond StoneSerif <BR>
/Times-Roman /Adobe-Garamond /StoneSerif <BR>
%%BeginFeature: *?FontQuery <BR>
save 4 dict begin <BR>
/sv exch def /str (fonts/ ) def /st2 128 string def <BR>
{
 <p>

 
 count 0 gt <BR>
{
 <p>

 
 dup st2 cvs (Font /) print print dup FontDirectory exch known <BR>
{
 <p>

 
 pop (: Yes) } <BR>
{
 <p>

 
 str exch st2 cvs dup length /len exch def <BR>
6 exch putinterval str 0 len 6 add getinterval mark exch <BR>
{
 <p>

 
 } st2 filenameforall counttomark <BR>
0 gt {
 <p>

 
? cleartomark (: Yes) }{
 <p>

 
 cleartomark (: No) }ifelse <BR>
} ifelse = flush <BR>
}{
 <p>

 
 exit } ifelse <BR>
} bind loop (*) = flush <BR>
sv end restore <BR>
%%EndFeature <BR>
%%?EndResourceQuery: Unknown <BR>
%%EOF<BR>
</CODE></div>
<BR>
The output from this sample program could be:<BR>
Font /StoneSerif: Yes <BR>
Font /Adobe-Garamond: No <BR>
Font /Times-Roman: No <BR>
*<BR>
<BR>
<B>%%?BeginVMStatus</B> (no keywords)<BR>
<BR>
<B>%%?EndVMStatus: </B>&lt; default&gt; &lt; default&gt; ::= &lt; text&gt;
(Default response)<BR>
This comment delimits PostScript language instructions that return the state
of the PostScript interpreter's VM. The standard response consists of a
line containing the results of the PostScript language vmstatus operator
as shown in Example 11:<BR>
Example 11<BR>
<BR>
<div class=code><CODE>
%!PS-Adobe-3.0 Query <BR>
%%Title: (VM status query) <BR>
%%?BeginVMStatus <BR>
vmstatus (Maximum: ) print = (Used: ) <BR>
print = (Save Level: ) <BR>
print = flush <BR>
%%?EndVMStatus: Unknown <BR>
%%EOF<BR>
</CODE></div>
<BR><A NAME="OSC"></A>
<h2>9 Open Structuring Conventions</h2><BR>
There is an open extension mechanism for the DSC comments. Its purpose is
to enable other vendors to extend the functionality of the DSC without having
to rely on Adobe to amend the official specification.<BR>
Vendors may need or want to embed extra information in a file beyond the
comments that Adobe has already specified. To facilitate this and to minimize
conflicts and difficulties for the vendor, Adobe maintains a registry of
comment prefixes that are allocated to vendors, and these comments may be
used in any way that is meaningful to those vendors. You may contact the
registry at the following address:<BR>
Adobe Systems Incorporated DSC Coordinator 1585 Charleston Road P.O. Box
7900 Mountain View, CA 94039-7900 (415) 961-4400<BR>
<BR><A NAME="Extension"></A>
<h2>9.1 The Extension Mechanism</h2><BR>
<BR>
All existing Adobe-specified comments in the DSC begin with the same prefix,
except one. Here is a quick summary of the syntax of existing comments:<BR>
The first line of a PostScript language file must, by convention, begin
with the characters %! (percent and exclamation, often referred to as &quot;percent-bang&quot;).
If the file is a conforming file, meaning that it conforms to the DSC version
3.0, then it is further qualified with PS-Adobe-3.0. This may be optionally
continued by some special keywords, such as EPSF or ExitServer, to identify
the entire file as a special instance. The first line of a PostScript language
file may look something like this:<BR>
<div class=code><CODE>
%!PS-Adobe-3.0 EPSF 3.0<BR>
</CODE></div>
This is the only Adobe-defined comment that does not begin with two percent
signs.<BR>
<BR>
All remaining structuring conventions, in their various forms, are represented
as comments beginning with two percent signs (%%) as the first characters
on the line.<BR>
The extension mechanism for the open structuring conventions is to use one
percent character followed immediately by a vendor-specific prefix of up
to five characters. Beyond those five characters the vendor who has registered
the prefix is responsible for the comments. The comment is terminated at
the end of the line.<BR>
Open structuring conventions may be used much like the existing DSC and
have similar syntax and philosophy. Here are some examples of fictitious
comments from made-up company prefixes:<BR>
%GCRImageName: myimage.ps %BCASpoolerName: local_spool 1.0 %BCACoverStock:
10129 %BCADocumentOrigin: (New York Office)<BR>
<BR><A NAME="Restrictions"></A>
<B>Restrictions</B><BR>
Adobe does not specify where in the document open structuring convention
comments can appear. However, the comments must not conflict in any way
with the regular parsing of document structuring conventions, and their
specification and use is otherwise truly open.<BR>
If these vendor-specific comments interact in some meaningful way with the
DSC, this interaction should be clearly specified by the creator of the
comments, and the description should specify the version number of the DSC
with which they interact.<BR>
The new comments, however implemented, should still follow the conforming
files restrictions discussed in section 3, &quot;DSC Conformance.&quot;<BR>
<BR><A NAME="ParsingRules"></A>
<B>Parsing Rules</B><BR>
Although the exact syntax of the vendor-specific comments is up to the vendor,
we strongly recommend adhering to the existing conventions and parsing rules
to simplify the task of writing parsing software.<BR>
Note The syntax and parsing rules for vendor-specific comments are up to
the vendor, and you should contact the vendor for details. The rules and
details supplied in this document are guidelines and suggestions that are
recom-mended, but are not enforced by Adobe.<BR>
<BR><A NAME="Special"></A>
<B>10 Special Structuring Conventions</B><BR>
There are two comments that do not readily fall into the other comment categories.
They are listed below, along with a description of when they should be used.<BR>
<BR><A NAME="BeginExitServer"></A>
<B>%%BeginExitServer: </B>&lt; password&gt; <BR>
&lt; password&gt; ::= &lt; text&gt;<BR>
<BR><A NAME="EndExitServer"></A>
<B>%%EndExitServer </B>(no keywords)<BR>
These comments delimit the PostScript language sequence that causes the
rest of the file to be executed as an unencapsulated job (see section 3.7.7,
&quot;Job Execution Environment&quot; of the PostScript Language Reference
Manual, Second Edition). This convention is used to flag any code that sets
up or executes the exitserver or startjob operators, so a document manager
can recognize and remove this sequence if necessary. The %%Begin(End)ExitServer
comments may be used with the %%EOF requirement convention to pinpoint where
the document manager should send an end-of-file indication. See the %!PS-Adobe-3.0
comment. PostScript language jobs that use exitserver or startjob should
be specially flagged with the %!PS-Adobe-3.0 ExitServer notation. An example
of appropriate use is shown in the following example:<BR>
<BR>
<div class=code><CODE>
%!PS-Adobe-3.0 <BR>
ExitServer <BR>
%%Title: (Example of exitserver usage) <BR>
%%EndComments <BR>
%%BeginExitServer: 000000 <BR>
serverdict begin 000000 exitserver <BR>
%%EndExitServer <BR>
...PostScript language instructions to perform persistent changes... <BR>
%%EOF<BR>
</CODE></div>
<BR><p>

<HR>
<A HREF="DSC2.php">back to previous page </A><BR>
<A HREF="DSC.php">DSC Index </A>
</BODY>
</HTML>
