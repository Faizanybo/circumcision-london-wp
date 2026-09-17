/* Gutenberg editor: clinic blocks, styles and Inspector controls.
   Uses wp.* globals. No build step. */
(function (wp) {
  'use strict';

  var el = wp.element.createElement;
  var Fragment = wp.element.Fragment;
  var registerBlockType = wp.blocks.registerBlockType;
  var registerBlockStyle = wp.blocks.registerBlockStyle;
  var useBlockProps = wp.blockEditor.useBlockProps;
  var InnerBlocks = wp.blockEditor.InnerBlocks;
  var InspectorControls = wp.blockEditor.InspectorControls;
  var BlockControls = wp.blockEditor.BlockControls;
  var MediaUpload = wp.blockEditor.MediaUpload;
  var MediaUploadCheck = wp.blockEditor.MediaUploadCheck;
  var MediaReplaceFlow = wp.blockEditor.MediaReplaceFlow;
  var PanelBody = wp.components.PanelBody;
  var TextControl = wp.components.TextControl;
  var TextareaControl = wp.components.TextareaControl;
  var ToggleControl = wp.components.ToggleControl;
  var SelectControl = wp.components.SelectControl;
  var Button = wp.components.Button;
  var ServerSideRender = wp.serverSideRender;
  var withSelect = wp.data && wp.data.withSelect ? wp.data.withSelect : null;

  function ssr(name, attrs) {
    return el(
      'div',
      { className: 'cil-editor-preview', key: name + ':' + JSON.stringify(attrs || {}) },
      el(ServerSideRender, { block: name, attributes: attrs, httpMethod: 'POST' })
    );
  }

  function fieldBox(label, children) {
    return el('div', { style: { marginBottom: '12px' } }, el('strong', { style: { display: 'block', marginBottom: '6px' } }, label), children);
  }

  function Repeater(props) {
    var items = props.items && props.items.length ? props.items.slice() : [];
    var blank = props.blank || {};

    function update(i, key, value) {
      var next = items.slice();
      next[i] = Object.assign({}, next[i] || {}, { [key]: value });
      props.onChange(next);
    }

    function add() {
      props.onChange(items.concat([Object.assign({}, blank)]));
    }

    function remove(i) {
      var next = items.slice();
      next.splice(i, 1);
      props.onChange(next);
    }

    return el(
      Fragment,
      {},
      items.map(function (item, i) {
        return el(
          'div',
          {
            key: i,
            style: { border: '1px solid #d9e3ee', borderRadius: '4px', padding: '10px', marginBottom: '10px' },
          },
          props.fields.map(function (field) {
            var Control = field.rows ? TextareaControl : TextControl;
            return el(Control, {
              key: field.key,
              label: field.label,
              value: item[field.key] || '',
              onChange: function (v) {
                update(i, field.key, v);
              },
              rows: field.rows || undefined,
            });
          }),
          el(
            Button,
            { isLink: true, isDestructive: true, onClick: function () { remove(i); } },
            'Remove'
          )
        );
      }),
      el(Button, { variant: 'secondary', onClick: add }, props.addLabel || 'Add row')
    );
  }

  function AttachmentControl(props) {
    var id = parseInt(props.value, 10) || 0;
    var allowed = props.allowedTypes || ['image'];
    var clearLabel = props.clearLabel || 'Use theme default';
    return el(
      'div',
      { style: { marginBottom: '14px' } },
      el('strong', { style: { display: 'block', marginBottom: '6px' } }, props.label || 'Media'),
      props.help
        ? el('p', { style: { fontSize: '12px', color: '#55637a', margin: '0 0 8px' } }, props.help)
        : null,
      el(
        MediaUploadCheck,
        {},
        el(MediaUpload, {
          onSelect: function (media) {
            if (props.onSelect) {
              props.onSelect(media || { id: 0 });
            }
          },
          allowedTypes: allowed,
          value: id,
          render: function (obj) {
            return el(
              Fragment,
              {},
              el(
                Button,
                { variant: 'secondary', onClick: obj.open },
                id ? 'Replace' : 'Select from Media Library'
              ),
              id
                ? el(
                    Button,
                    {
                      isLink: true,
                      isDestructive: true,
                      style: { marginLeft: '8px' },
                      onClick: function () {
                        if (props.onSelect) {
                          props.onSelect({ id: 0, url: '' });
                        }
                      },
                    },
                    clearLabel
                  )
                : null,
              id ? el('p', { style: { fontSize: '12px', margin: '6px 0 0' } }, 'Attachment ID ' + id) : null
            );
          },
        })
      )
    );
  }

  function dynamicBlock(name, title, extra) {
    extra = extra || {};
    registerBlockType(name, {
      apiVersion: 3,
      title: title,
      category: 'circumcision-london',
      icon: extra.icon || 'layout',
      supports: { html: false, align: ['wide', 'full'] },
      attributes: extra.attributes || {},
      edit: function (props) {
        return el(
          Fragment,
          {},
          extra.inspect ? el(InspectorControls, {}, extra.inspect(props)) : null,
          el('div', useBlockProps({ className: 'cil-editor-canvas' }), ssr(name, props.attributes))
        );
      },
      save: function () {
        return null;
      },
    });
  }

  /* ------------------------------------------------------------------ styles */
  registerBlockStyle('core/group', { name: 'cil-card', label: 'Clinic card' });
  registerBlockStyle('core/group', { name: 'cil-callout', label: 'Callout' });
  registerBlockStyle('core/group', { name: 'cil-callout-urgent', label: 'Urgent callout' });
  registerBlockStyle('core/group', { name: 'cil-wrap', label: 'Wrap 1220' });
  registerBlockStyle('core/group', { name: 'cil-wrap-narrow', label: 'Wrap 780' });
  registerBlockStyle('core/columns', { name: 'cil-split', label: 'Split' });
  registerBlockStyle('core/quote', { name: 'cil-quote', label: 'Testimonial' });
  registerBlockStyle('core/image', { name: 'cil-figure-4-3', label: 'Figure 4:3' });
  registerBlockStyle('core/image', { name: 'cil-figure-5-4', label: 'Figure 5:4' });
  registerBlockStyle('core/image', { name: 'cil-figure-3-4', label: 'Figure 3:4' });

  /* -------------------------------------------------------- layout blocks */
  registerBlockType('cil/section', {
    apiVersion: 3,
    title: 'Section',
    category: 'circumcision-london',
    icon: 'align-wide',
    description: 'Prototype section / section-sm with wrap and optional band.',
    supports: { html: false, align: ['full', 'wide'], anchor: true },
    attributes: {
      size: { type: 'string', default: 'section-sm' },
      band: { type: 'string', default: '' },
      wrap: { type: 'string', default: 'wrap' },
      anchor: { type: 'string', default: '' },
    },
    edit: function (props) {
      var cls = [props.attributes.size, props.attributes.band].filter(Boolean).join(' ');
      return el(
        Fragment,
        {},
        el(
          InspectorControls,
          {},
          el(
            PanelBody,
            { title: 'Section layout' },
            el(SelectControl, {
              label: 'Padding',
              value: props.attributes.size,
              options: [
                { label: 'Small (section-sm)', value: 'section-sm' },
                { label: 'Standard (section)', value: 'section' },
              ],
              onChange: function (v) {
                props.setAttributes({ size: v });
              },
            }),
            el(SelectControl, {
              label: 'Band',
              value: props.attributes.band,
              options: [
                { label: 'None', value: '' },
                { label: 'White + edge', value: 'bg-card edge' },
                { label: 'Warm paper', value: 'bg-warm' },
              ],
              onChange: function (v) {
                props.setAttributes({ band: v });
              },
            }),
            el(SelectControl, {
              label: 'Width',
              value: props.attributes.wrap,
              options: [
                { label: 'Wide (1220)', value: 'wrap' },
                { label: 'Narrow (780)', value: 'wrap-narrow' },
              ],
              onChange: function (v) {
                props.setAttributes({ wrap: v });
              },
            }),
            el(TextControl, {
              label: 'HTML anchor',
              help: 'Used for in-page links such as /team#haidar',
              value: props.attributes.anchor,
              onChange: function (v) {
                props.setAttributes({ anchor: v.replace(/^#/, '') });
              },
            })
          )
        ),
        el(
          'section',
          useBlockProps({ className: cls }),
          el('div', { className: props.attributes.wrap }, el(InnerBlocks))
        )
      );
    },
    save: function (props) {
      var cls = [props.attributes.size, props.attributes.band].filter(Boolean).join(' ');
      return el(
        'section',
        useBlockProps.save({ className: cls }),
        el('div', { className: props.attributes.wrap }, el(InnerBlocks.Content))
      );
    },
  });

  registerBlockType('cil/split', {
    apiVersion: 3,
    title: 'Split',
    category: 'circumcision-london',
    icon: 'columns',
    description: 'Two-column split. Reverse stacks the first column below on small screens.',
    supports: { html: false, align: ['wide', 'full'] },
    attributes: {
      reverse: { type: 'boolean', default: false },
    },
    edit: function (props) {
      var cls = 'split' + (props.attributes.reverse ? ' reverse' : '');
      return el(
        Fragment,
        {},
        el(
          InspectorControls,
          {},
          el(
            PanelBody,
            { title: 'Split' },
            el(ToggleControl, {
              label: 'Reverse on small screens',
              checked: !!props.attributes.reverse,
              onChange: function (v) {
                props.setAttributes({ reverse: v });
              },
            })
          )
        ),
        el(
          'div',
          useBlockProps({ className: cls }),
          el(InnerBlocks, {
            template: [
              ['core/group', {}, [['core/paragraph']]],
              ['core/group', {}, [['core/paragraph']]],
            ],
            templateLock: false,
          })
        )
      );
    },
    save: function (props) {
      var cls = 'split' + (props.attributes.reverse ? ' reverse' : '');
      return el('div', useBlockProps.save({ className: cls }), el(InnerBlocks.Content));
    },
  });

  /* ----------------------------------------------------- dynamic clinic */
  registerBlockType('cil/cliniko-bookings', {
    apiVersion: 3,
    title: 'Cliniko bookings',
    category: 'circumcision-london',
    icon: 'calendar-alt',
    description: 'London Cliniko booking diary. Do not use for Luton, Southampton or Birmingham.',
    supports: { html: false, align: ['wide', 'full'] },
    edit: function () {
      return el(
        'div',
        useBlockProps({ className: 'cil-cliniko-bookings-editor' }),
        el('p', { className: 'caps', style: { marginTop: 0 } }, 'London Cliniko diary'),
        el(
          'p',
          { style: { marginBottom: 0 } },
          'The live calendar is shown on the published page. Do not add this block to Luton, Southampton or Birmingham pages.'
        )
      );
    },
    save: function () {
      return null;
    },
  });

  dynamicBlock('cil/trust-strip', 'Trust strip', { icon: 'shield' });

  dynamicBlock('cil/urgent-note', 'Urgent A&E note', { icon: 'warning' });

  dynamicBlock('cil/group-cards', 'Age group cards', {
    icon: 'groups',
    attributes: {
      level: { type: 'number', default: 3 },
      exclude: { type: 'string', default: '' },
      banded: { type: 'boolean', default: false },
    },
    inspect: function (props) {
      return el(
        PanelBody,
        { title: 'Cards' },
        el(SelectControl, {
          label: 'Heading level',
          value: String(props.attributes.level),
          options: [
            { label: 'H2', value: '2' },
            { label: 'H3', value: '3' },
          ],
          onChange: function (v) {
            props.setAttributes({ level: parseInt(v, 10) });
          },
        }),
        el(TextControl, {
          label: 'Exclude URL (optional)',
          help: 'Full page URL of the current age group, to hide that card.',
          value: props.attributes.exclude,
          onChange: function (v) {
            props.setAttributes({ exclude: v });
          },
        }),
        el(ToggleControl, {
          label: 'Wrap in warm band',
          checked: !!props.attributes.banded,
          onChange: function (v) {
            props.setAttributes({ banded: v });
          },
        })
      );
    },
  });

  dynamicBlock('cil/hero', 'Homepage hero', {
    icon: 'cover-image',
    attributes: {
      eyebrow: { type: 'string', default: 'CQC registered · Edgware, North-West London' },
      title: { type: 'string', default: 'A dedicated circumcision clinic in North-West London' },
      sub: {
        type: 'string',
        default:
          'Qualified practitioners, local anaesthetic every time, and we show you that no pain is felt before we begin.',
      },
      posterId: { type: 'number', default: 0 },
      videoMp4Id: { type: 'number', default: 0 },
      videoWebmId: { type: 'number', default: 0 },
    },
    inspect: function (props) {
      return el(
        Fragment,
        {},
        el(
          PanelBody,
          { title: 'Hero copy' },
          el(TextControl, {
            label: 'Eyebrow',
            value: props.attributes.eyebrow,
            onChange: function (v) {
              props.setAttributes({ eyebrow: v });
            },
          }),
          el(TextareaControl, {
            label: 'Heading',
            value: props.attributes.title,
            onChange: function (v) {
              props.setAttributes({ title: v });
            },
          }),
          el(TextareaControl, {
            label: 'Subheading',
            value: props.attributes.sub,
            onChange: function (v) {
              props.setAttributes({ sub: v });
            },
          })
        ),
        el(
          PanelBody,
          { title: 'Media', initialOpen: false },
          el(AttachmentControl, {
            label: 'Poster image',
            help: 'Leave empty to keep the current theme poster.',
            allowedTypes: ['image'],
            value: props.attributes.posterId,
            onSelect: function (media) {
              props.setAttributes({ posterId: media.id || 0 });
            },
          }),
          el(AttachmentControl, {
            label: 'Hero video (MP4)',
            help: 'Optional Media Library MP4. Large clinic videos are better hosted outside WordPress.',
            allowedTypes: ['video'],
            value: props.attributes.videoMp4Id,
            onSelect: function (media) {
              props.setAttributes({ videoMp4Id: media.id || 0 });
            },
          }),
          el(AttachmentControl, {
            label: 'Hero video (WebM)',
            allowedTypes: ['video'],
            value: props.attributes.videoWebmId,
            onSelect: function (media) {
              props.setAttributes({ videoWebmId: media.id || 0 });
            },
          })
        )
      );
    },
  });

  dynamicBlock('cil/groups', 'Homepage age groups', {
    icon: 'groups',
    attributes: {
      level: { type: 'number', default: 2 },
    },
    inspect: function (props) {
      return el(
        PanelBody,
        { title: 'Cards' },
        el(SelectControl, {
          label: 'Heading level',
          value: String(props.attributes.level),
          options: [
            { label: 'H2', value: '2' },
            { label: 'H3', value: '3' },
          ],
          onChange: function (v) {
            props.setAttributes({ level: parseInt(v, 10) });
          },
        })
      );
    },
  });

  dynamicBlock('cil/intro', 'Homepage introduction', {
    icon: 'id',
    attributes: {
      eyebrow: { type: 'string', default: 'Welcome to the clinic' },
      heading: {
        type: 'string',
        default: 'Choosing circumcision is an important decision. We will talk you through all of it.',
      },
      html: { type: 'string', default: '' },
      imageId: { type: 'number', default: 0 },
    },
    inspect: function (props) {
      return el(
        Fragment,
        {},
        el(
          PanelBody,
          { title: 'Introduction' },
          el(TextControl, {
            label: 'Eyebrow',
            value: props.attributes.eyebrow,
            onChange: function (v) {
              props.setAttributes({ eyebrow: v });
            },
          }),
          el(TextareaControl, {
            label: 'Heading',
            value: props.attributes.heading,
            onChange: function (v) {
              props.setAttributes({ heading: v });
            },
          }),
          el(TextareaControl, {
            label: 'Body HTML',
            help: 'Leave blank to use the current default paragraphs.',
            value: props.attributes.html,
            onChange: function (v) {
              props.setAttributes({ html: v });
            },
          })
        ),
        el(
          PanelBody,
          { title: 'Photograph', initialOpen: false },
          el(AttachmentControl, {
            label: 'Practitioner photograph',
            help: 'Leave empty to keep the current theme image of Dr Haidar.',
            allowedTypes: ['image'],
            value: props.attributes.imageId,
            onSelect: function (media) {
              props.setAttributes({ imageId: media.id || 0 });
            },
          })
        )
      );
    },
  });

  dynamicBlock('cil/process', 'Homepage process', {
    icon: 'editor-ol',
    attributes: {
      eyebrow: { type: 'string', default: 'What happens' },
      heading: { type: 'string', default: 'Four steps, and no surprises in any of them' },
      lede: {
        type: 'string',
        default:
          'This is the whole process. If anything on the day differs from what is written here, we will have told you why before it happens.',
      },
      items: { type: 'array', default: [] },
    },
    inspect: function (props) {
      return el(
        PanelBody,
        { title: 'Process' },
        el(TextControl, {
          label: 'Eyebrow',
          value: props.attributes.eyebrow,
          onChange: function (v) {
            props.setAttributes({ eyebrow: v });
          },
        }),
        el(TextareaControl, {
          label: 'Heading',
          value: props.attributes.heading,
          onChange: function (v) {
            props.setAttributes({ heading: v });
          },
        }),
        el(TextareaControl, {
          label: 'Lede',
          value: props.attributes.lede,
          onChange: function (v) {
            props.setAttributes({ lede: v });
          },
        }),
        el(Repeater, {
          items: props.attributes.items,
          blank: { h: '', p: '' },
          fields: [
            { key: 'h', label: 'Step heading' },
            { key: 'p', label: 'Step text', rows: 3 },
          ],
          addLabel: 'Add step',
          onChange: function (next) {
            props.setAttributes({ items: next });
          },
        })
      );
    },
  });

  dynamicBlock('cil/home-callback', 'Homepage callback', {
    icon: 'email-alt',
    attributes: {
      formId: { type: 'string', default: 'home' },
      subject: { type: 'string', default: 'homepage callback' },
      cardEyebrow: { type: 'string', default: 'Request a call back' },
      cardTitle: { type: 'string', default: 'Ask before you book' },
      eyebrow: { type: 'string', default: 'Being straight with you' },
      heading: { type: 'string', default: 'When we will tell you not to' },
      html: { type: 'string', default: '' },
    },
    inspect: function (props) {
      return el(
        PanelBody,
        { title: 'Callback section' },
        el(TextControl, {
          label: 'Form eyebrow',
          value: props.attributes.cardEyebrow,
          onChange: function (v) {
            props.setAttributes({ cardEyebrow: v });
          },
        }),
        el(TextControl, {
          label: 'Form heading',
          value: props.attributes.cardTitle,
          onChange: function (v) {
            props.setAttributes({ cardTitle: v });
          },
        }),
        el(TextControl, {
          label: 'Copy eyebrow',
          value: props.attributes.eyebrow,
          onChange: function (v) {
            props.setAttributes({ eyebrow: v });
          },
        }),
        el(TextareaControl, {
          label: 'Copy heading',
          value: props.attributes.heading,
          onChange: function (v) {
            props.setAttributes({ heading: v });
          },
        }),
        el(TextareaControl, {
          label: 'Body HTML',
          help: 'Leave blank to use the current default paragraphs.',
          value: props.attributes.html,
          onChange: function (v) {
            props.setAttributes({ html: v });
          },
        }),
        el(TextControl, {
          label: 'Form ID',
          value: props.attributes.formId,
          onChange: function (v) {
            props.setAttributes({ formId: v });
          },
        }),
        el(TextControl, {
          label: 'Subject',
          value: props.attributes.subject,
          onChange: function (v) {
            props.setAttributes({ subject: v });
          },
        })
      );
    },
  });

  dynamicBlock('cil/reviews', 'Homepage reviews', {
    icon: 'star-filled',
    attributes: {
      body: { type: 'string', default: '' },
      quotes: { type: 'array', default: [] },
    },
    inspect: function (props) {
      return el(
        PanelBody,
        { title: 'Reviews' },
        el(TextareaControl, {
          label: 'Body paragraph',
          help: 'Leave blank to use the current default paragraph. Rating figures stay live from clinic data.',
          value: props.attributes.body,
          onChange: function (v) {
            props.setAttributes({ body: v });
          },
        }),
        el(Repeater, {
          items: props.attributes.quotes,
          blank: { name: '', context: '', quote: '' },
          fields: [
            { key: 'name', label: 'Name' },
            { key: 'context', label: 'Context' },
            { key: 'quote', label: 'Quote', rows: 4 },
          ],
          addLabel: 'Add quote',
          onChange: function (next) {
            props.setAttributes({ quotes: next });
          },
        })
      );
    },
  });

  dynamicBlock('cil/callback-form', 'Callback form', {
    icon: 'email',
    attributes: {
      formId: { type: 'string', default: 'callback' },
      subject: { type: 'string', default: 'general enquiry' },
    },
    inspect: function (props) {
      return el(
        PanelBody,
        { title: 'Form' },
        el(TextControl, {
          label: 'Form ID',
          value: props.attributes.formId,
          onChange: function (v) {
            props.setAttributes({ formId: v });
          },
        }),
        el(TextControl, {
          label: 'Subject',
          value: props.attributes.subject,
          onChange: function (v) {
            props.setAttributes({ subject: v });
          },
        })
      );
    },
  });

  dynamicBlock('cil/callback-card', 'Callback card', {
    icon: 'email-alt',
    attributes: {
      eyebrow: { type: 'string', default: 'Request a call back' },
      title: { type: 'string', default: 'Ask us first' },
      formId: { type: 'string', default: 'callback' },
      subject: { type: 'string', default: 'general enquiry' },
      urgent: { type: 'boolean', default: false },
    },
    inspect: function (props) {
      return el(
        PanelBody,
        { title: 'Card' },
        el(TextControl, {
          label: 'Eyebrow',
          value: props.attributes.eyebrow,
          onChange: function (v) {
            props.setAttributes({ eyebrow: v });
          },
        }),
        el(TextControl, {
          label: 'Heading',
          value: props.attributes.title,
          onChange: function (v) {
            props.setAttributes({ title: v });
          },
        }),
        el(TextControl, {
          label: 'Form ID',
          value: props.attributes.formId,
          onChange: function (v) {
            props.setAttributes({ formId: v });
          },
        }),
        el(TextControl, {
          label: 'Subject',
          value: props.attributes.subject,
          onChange: function (v) {
            props.setAttributes({ subject: v });
          },
        }),
        el(ToggleControl, {
          label: 'Show urgent A&E note underneath',
          checked: !!props.attributes.urgent,
          onChange: function (v) {
            props.setAttributes({ urgent: v });
          },
        })
      );
    },
  });

  dynamicBlock('cil/cta-band', 'Talk to us band', {
    icon: 'megaphone',
    attributes: {
      title: { type: 'string', default: 'Ask us anything before you decide' },
      text: {
        type: 'string',
        default:
          'Most people call with a question rather than to book. That is what the phone is for, and nothing is booked until you say so.',
      },
    },
    inspect: function (props) {
      return el(
        PanelBody,
        { title: 'Copy' },
        el(TextControl, {
          label: 'Heading',
          value: props.attributes.title,
          onChange: function (v) {
            props.setAttributes({ title: v });
          },
        }),
        el(TextareaControl, {
          label: 'Lede',
          value: props.attributes.text,
          onChange: function (v) {
            props.setAttributes({ text: v });
          },
        })
      );
    },
  });

  dynamicBlock('cil/page-head', 'Page heading', {
    icon: 'heading',
    attributes: {
      eyebrow: { type: 'string', default: '' },
      title: { type: 'string', default: 'Page title' },
      lede: { type: 'string', default: '' },
      reviewedBy: { type: 'string', default: '' },
      crumbs: { type: 'array', default: [] },
    },
    inspect: function (props) {
      return el(
        PanelBody,
        { title: 'Heading' },
        el(TextControl, {
          label: 'Eyebrow',
          value: props.attributes.eyebrow,
          onChange: function (v) {
            props.setAttributes({ eyebrow: v });
          },
        }),
        el(TextControl, {
          label: 'Title',
          value: props.attributes.title,
          onChange: function (v) {
            props.setAttributes({ title: v });
          },
        }),
        el(TextareaControl, {
          label: 'Lede',
          value: props.attributes.lede,
          onChange: function (v) {
            props.setAttributes({ lede: v });
          },
        }),
        el(TextControl, {
          label: 'Reviewed by (HTML allowed)',
          help: 'Leave blank to omit. Home is always prepended to crumbs.',
          value: props.attributes.reviewedBy,
          onChange: function (v) {
            props.setAttributes({ reviewedBy: v });
          },
        }),
        fieldBox(
          'Breadcrumbs (after Home)',
          el(Repeater, {
            items: props.attributes.crumbs,
            blank: { label: '', href: '' },
            fields: [
              { key: 'label', label: 'Label' },
              { key: 'href', label: 'URL (blank on last crumb)' },
            ],
            addLabel: 'Add crumb',
            onChange: function (next) {
              props.setAttributes({ crumbs: next });
            },
          })
        )
      );
    },
  });

  dynamicBlock('cil/section-head', 'Section heading', {
    icon: 'editor-textcolor',
    attributes: {
      eyebrow: { type: 'string', default: '' },
      heading: { type: 'string', default: '' },
      lede: { type: 'string', default: '' },
      display: { type: 'string', default: 'd-2' },
    },
    inspect: function (props) {
      return el(
        PanelBody,
        { title: 'Heading' },
        el(TextControl, {
          label: 'Eyebrow',
          value: props.attributes.eyebrow,
          onChange: function (v) {
            props.setAttributes({ eyebrow: v });
          },
        }),
        el(TextControl, {
          label: 'Heading',
          value: props.attributes.heading,
          onChange: function (v) {
            props.setAttributes({ heading: v });
          },
        }),
        el(TextareaControl, {
          label: 'Lede',
          value: props.attributes.lede,
          onChange: function (v) {
            props.setAttributes({ lede: v });
          },
        }),
        el(SelectControl, {
          label: 'Display size',
          value: props.attributes.display,
          options: [
            { label: 'd-1', value: 'd-1' },
            { label: 'd-2', value: 'd-2' },
            { label: 'd-3', value: 'd-3' },
          ],
          onChange: function (v) {
            props.setAttributes({ display: v });
          },
        })
      );
    },
  });

  dynamicBlock('cil/spec-list', 'Specification list', {
    icon: 'list-view',
    attributes: {
      rows: {
        type: 'array',
        default: [
          { k: 'Best age', v: 'Under one month old' },
          { k: 'Price', v: 'From £200' },
        ],
      },
    },
    inspect: function (props) {
      return el(
        PanelBody,
        { title: 'Rows' },
        el(Repeater, {
          items: props.attributes.rows,
          blank: { k: '', v: '' },
          fields: [
            { key: 'k', label: 'Label' },
            { key: 'v', label: 'Value (HTML allowed)' },
          ],
          onChange: function (next) {
            props.setAttributes({ rows: next });
          },
        })
      );
    },
  });

  dynamicBlock('cil/steps', 'Process steps', {
    icon: 'editor-ol',
    attributes: {
      items: {
        type: 'array',
        default: [
          { h: 'You call, or ask us to call you', p: 'We answer the questions you have now and agree a consultation time.' },
          { h: 'Consultation and examination', p: 'You meet the practitioner who would carry out the procedure.' },
        ],
      },
    },
    inspect: function (props) {
      return el(
        PanelBody,
        { title: 'Steps' },
        el(Repeater, {
          items: props.attributes.items,
          blank: { h: '', p: '' },
          fields: [
            { key: 'h', label: 'Heading' },
            { key: 'p', label: 'Text', rows: 3 },
          ],
          addLabel: 'Add step',
          onChange: function (next) {
            props.setAttributes({ items: next });
          },
        })
      );
    },
  });

  dynamicBlock('cil/callout', 'Callout', {
    icon: 'info',
    attributes: {
      title: { type: 'string', default: '' },
      body: { type: 'string', default: '' },
      urgent: { type: 'boolean', default: false },
      level: { type: 'number', default: 2 },
    },
    inspect: function (props) {
      return el(
        PanelBody,
        { title: 'Callout' },
        el(TextControl, {
          label: 'Heading',
          value: props.attributes.title,
          onChange: function (v) {
            props.setAttributes({ title: v });
          },
        }),
        el(TextareaControl, {
          label: 'Body (HTML allowed)',
          value: props.attributes.body,
          onChange: function (v) {
            props.setAttributes({ body: v });
          },
        }),
        el(ToggleControl, {
          label: 'Urgent wash',
          checked: !!props.attributes.urgent,
          onChange: function (v) {
            props.setAttributes({ urgent: v });
          },
        }),
        el(SelectControl, {
          label: 'Heading level',
          value: String(props.attributes.level),
          options: [
            { label: 'H2', value: '2' },
            { label: 'H3', value: '3' },
          ],
          onChange: function (v) {
            props.setAttributes({ level: parseInt(v, 10) });
          },
        })
      );
    },
  });

  dynamicBlock('cil/quote', 'Testimonial card', {
    icon: 'format-quote',
    attributes: {
      name: { type: 'string', default: '' },
      context: { type: 'string', default: '' },
      quote: { type: 'string', default: '' },
    },
    inspect: function (props) {
      return el(
        PanelBody,
        { title: 'Quote' },
        el(TextareaControl, {
          label: 'Quote',
          value: props.attributes.quote,
          onChange: function (v) {
            props.setAttributes({ quote: v });
          },
        }),
        el(TextControl, {
          label: 'Name',
          value: props.attributes.name,
          onChange: function (v) {
            props.setAttributes({ name: v });
          },
        }),
        el(TextControl, {
          label: 'Context',
          value: props.attributes.context,
          onChange: function (v) {
            props.setAttributes({ context: v });
          },
        })
      );
    },
  });

  dynamicBlock('cil/quotes-grid', 'Testimonial grid', {
    icon: 'testimonial',
    attributes: {
      quotes: { type: 'array', default: [] },
    },
    inspect: function (props) {
      return el(
        PanelBody,
        { title: 'Quotes' },
        el(
          'p',
          { style: { fontSize: '13px', color: '#55637a' } },
          'Leave empty to use the three homepage testimonials.'
        ),
        el(Repeater, {
          items: props.attributes.quotes,
          blank: { name: '', context: '', quote: '' },
          fields: [
            { key: 'quote', label: 'Quote', rows: 4 },
            { key: 'name', label: 'Name' },
            { key: 'context', label: 'Context' },
          ],
          addLabel: 'Add quote',
          onChange: function (next) {
            props.setAttributes({ quotes: next });
          },
        })
      );
    },
  });

  dynamicBlock('cil/price-table', 'Price table', {
    icon: 'money-alt',
    attributes: {
      rows: {
        type: 'array',
        default: [{ name: '0 – 2 months', desc: '', price: '£200', href: '', url: '', cta: 'Book this' }],
      },
    },
    inspect: function (props) {
      return el(
        PanelBody,
        { title: 'Rows' },
        el(Repeater, {
          items: props.attributes.rows,
          blank: { name: '', desc: '', price: '', href: '', url: '', cta: 'Book this' },
          fields: [
            { key: 'name', label: 'Name' },
            { key: 'desc', label: 'Description', rows: 2 },
            { key: 'price', label: 'Price' },
            { key: 'href', label: 'Name link (optional)' },
            { key: 'url', label: 'Button URL (blank = book page)' },
            { key: 'cta', label: 'Button label' },
          ],
          onChange: function (next) {
            props.setAttributes({ rows: next });
          },
        })
      );
    },
  });

  dynamicBlock('cil/faq', 'FAQ accordion', {
    icon: 'editor-help',
    attributes: {
      heading: { type: 'string', default: 'Questions people ask us' },
      items: { type: 'array', default: [] },
    },
    inspect: function (props) {
      return el(
        PanelBody,
        { title: 'Questions' },
        el(TextControl, {
          label: 'Heading',
          value: props.attributes.heading,
          onChange: function (v) {
            props.setAttributes({ heading: v });
          },
        }),
        el(
          'p',
          { style: { fontSize: '13px', color: '#55637a' } },
          'Leave the list empty to use the homepage FAQs.'
        ),
        el(Repeater, {
          items: props.attributes.items,
          blank: { q: '', a: '' },
          fields: [
            { key: 'q', label: 'Question' },
            { key: 'a', label: 'Answer HTML', rows: 4 },
          ],
          addLabel: 'Add question',
          onChange: function (next) {
            props.setAttributes({ items: next });
          },
        })
      );
    },
  });

  dynamicBlock('cil/rating-badge', 'Rating badge', {
    icon: 'star-filled',
    attributes: {
      score: { type: 'string', default: '4.9' },
      strong: { type: 'string', default: 'Read them on Google' },
      sub: { type: 'string', default: '2,092 reviews, new tab' },
      href: { type: 'string', default: '' },
      track: { type: 'string', default: 'reviews-read' },
      smallScore: { type: 'boolean', default: false },
    },
    inspect: function (props) {
      return el(
        PanelBody,
        { title: 'Badge' },
        el(TextControl, {
          label: 'Score',
          value: props.attributes.score,
          onChange: function (v) {
            props.setAttributes({ score: v });
          },
        }),
        el(TextControl, {
          label: 'Title',
          value: props.attributes.strong,
          onChange: function (v) {
            props.setAttributes({ strong: v });
          },
        }),
        el(TextControl, {
          label: 'Subtitle',
          value: props.attributes.sub,
          onChange: function (v) {
            props.setAttributes({ sub: v });
          },
        }),
        el(TextControl, {
          label: 'URL (blank = Google reviews)',
          value: props.attributes.href,
          onChange: function (v) {
            props.setAttributes({ href: v });
          },
        }),
        el(ToggleControl, {
          label: 'Smaller score (CQC style)',
          checked: !!props.attributes.smallScore,
          onChange: function (v) {
            props.setAttributes({ smallScore: v });
          },
        })
      );
    },
  });

  dynamicBlock('cil/video-grid', 'Video testimonials', {
    icon: 'video-alt3',
    attributes: {
      items: { type: 'array', default: [] },
    },
    inspect: function (props) {
      var items = props.attributes.items && props.attributes.items.length ? props.attributes.items.slice() : [];
      function updateItem(i, patch) {
        var next = items.slice();
        next[i] = Object.assign({}, next[i] || {}, patch);
        props.setAttributes({ items: next });
      }
      return el(
        PanelBody,
        { title: 'Videos' },
        el(
          'p',
          { style: { fontSize: '13px', color: '#55637a' } },
          'Renders nothing until at least one video file or URL is added. Large videos should use an external URL, not a WordPress upload.'
        ),
        items.map(function (item, i) {
          return el(
            'div',
            {
              key: i,
              style: { border: '1px solid #d9e3ee', borderRadius: '4px', padding: '10px', marginBottom: '10px' },
            },
            el(TextControl, {
              label: 'Caption',
              value: item.title || '',
              onChange: function (v) {
                updateItem(i, { title: v });
              },
            }),
            el(AttachmentControl, {
              label: 'Poster image',
              allowedTypes: ['image'],
              value: item.posterId,
              clearLabel: 'Remove',
              onSelect: function (media) {
                updateItem(i, { posterId: media.id || 0 });
              },
            }),
            el(AttachmentControl, {
              label: 'MP4',
              allowedTypes: ['video'],
              value: item.mp4Id,
              clearLabel: 'Remove',
              onSelect: function (media) {
                updateItem(i, { mp4Id: media.id || 0 });
              },
            }),
            el(AttachmentControl, {
              label: 'WebM',
              allowedTypes: ['video'],
              value: item.webmId,
              clearLabel: 'Remove',
              onSelect: function (media) {
                updateItem(i, { webmId: media.id || 0 });
              },
            }),
            el(TextControl, {
              label: 'Poster URL (optional)',
              value: item.poster || '',
              onChange: function (v) {
                updateItem(i, { poster: v });
              },
            }),
            el(TextControl, {
              label: 'MP4 URL (optional)',
              help: 'Use for videos hosted outside WordPress.',
              value: item.mp4 || '',
              onChange: function (v) {
                updateItem(i, { mp4: v });
              },
            }),
            el(TextControl, {
              label: 'WebM URL (optional)',
              value: item.webm || '',
              onChange: function (v) {
                updateItem(i, { webm: v });
              },
            }),
            el(
              Button,
              {
                isLink: true,
                isDestructive: true,
                onClick: function () {
                  var next = items.slice();
                  next.splice(i, 1);
                  props.setAttributes({ items: next });
                },
              },
              'Remove video'
            )
          );
        }),
        el(
          Button,
          {
            variant: 'secondary',
            onClick: function () {
              props.setAttributes({
                items: items.concat([
                  { title: '', poster: '', mp4: '', webm: '', posterId: 0, mp4Id: 0, webmId: 0 },
                ]),
              });
            },
          },
          'Add video'
        )
      );
    },
  });

  dynamicBlock('cil/spec-panel', 'At-a-glance spec', {
    icon: 'index-card',
    attributes: {
      eyebrow: { type: 'string', default: 'At a glance' },
      rows: { type: 'array', default: [] },
      note: { type: 'string', default: '' },
    },
    inspect: function (props) {
      return el(
        PanelBody,
        { title: 'Spec' },
        el(TextControl, {
          label: 'Eyebrow',
          value: props.attributes.eyebrow,
          onChange: function (v) {
            props.setAttributes({ eyebrow: v });
          },
        }),
        el(Repeater, {
          items: props.attributes.rows,
          blank: { k: '', v: '' },
          fields: [
            { key: 'k', label: 'Label' },
            { key: 'v', label: 'Value (HTML allowed)' },
          ],
          onChange: function (next) {
            props.setAttributes({ rows: next });
          },
        }),
        el(TextareaControl, {
          label: 'Note under the list (HTML allowed)',
          value: props.attributes.note,
          onChange: function (v) {
            props.setAttributes({ note: v });
          },
        })
      );
    },
  });

  dynamicBlock('cil/text-section', 'Text section', {
    icon: 'media-text',
    attributes: {
      eyebrow: { type: 'string', default: '' },
      heading: { type: 'string', default: '' },
      html: { type: 'string', default: '' },
      narrow: { type: 'boolean', default: true },
      banded: { type: 'boolean', default: false },
      display: { type: 'string', default: 'd-2' },
    },
    inspect: function (props) {
      return el(
        PanelBody,
        { title: 'Section' },
        el(TextControl, {
          label: 'Eyebrow',
          value: props.attributes.eyebrow,
          onChange: function (v) {
            props.setAttributes({ eyebrow: v });
          },
        }),
        el(TextControl, {
          label: 'Heading',
          value: props.attributes.heading,
          onChange: function (v) {
            props.setAttributes({ heading: v });
          },
        }),
        el(TextareaControl, {
          label: 'Body HTML',
          help: 'Include the prototype body-text wrapper.',
          value: props.attributes.html,
          onChange: function (v) {
            props.setAttributes({ html: v });
          },
        }),
        el(ToggleControl, {
          label: 'Narrow wrap (780px)',
          checked: props.attributes.narrow !== false,
          onChange: function (v) {
            props.setAttributes({ narrow: v });
          },
        }),
        el(ToggleControl, {
          label: 'White band + edge',
          checked: !!props.attributes.banded,
          onChange: function (v) {
            props.setAttributes({ banded: v });
          },
        })
      );
    },
  });

  function applyFigureMedia(props, media) {
    var id = media && media.id ? parseInt(media.id, 10) : 0;
    var next = {
      imageId: id || 0,
      src: media && media.url ? media.url : '',
      name: '',
    };
    if (media && media.alt) {
      next.alt = media.alt;
    }
    if (media && media.width) {
      next.width = media.width;
    }
    if (media && media.height) {
      next.height = media.height;
    }
    props.setAttributes(next);
    if (
      props.attributes.useFeaturedImage &&
      wp.data &&
      wp.data.dispatch &&
      wp.data.dispatch('core/editor') &&
      typeof wp.data.dispatch('core/editor').editPost === 'function'
    ) {
      wp.data.dispatch('core/editor').editPost({ featured_media: id || 0 });
    }
  }

  function FigureEdit(props) {
    var attrs = props.attributes;
    var previewAttrs = Object.assign({}, attrs);
    if (attrs.useFeaturedImage && props.featuredId) {
      previewAttrs.imageId = parseInt(props.featuredId, 10) || 0;
      previewAttrs.useFeaturedImage = false;
    }
    var mediaId = parseInt(previewAttrs.imageId, 10) || 0;
    return el(
      Fragment,
      {},
      el(
        BlockControls,
        {},
        MediaReplaceFlow
          ? el(MediaReplaceFlow, {
              mediaId: mediaId,
              mediaURL: attrs.src || '',
              allowedTypes: ['image'],
              accept: 'image/*',
              onSelect: function (media) {
                applyFigureMedia(props, media);
              },
            })
          : null
      ),
      el(
        InspectorControls,
        {},
        el(
          PanelBody,
          { title: 'Image' },
          el(ToggleControl, {
            label: 'Use featured image',
            help: 'When on, replacing the Featured image in the document sidebar updates this photograph.',
            checked: !!attrs.useFeaturedImage,
            onChange: function (v) {
              props.setAttributes({ useFeaturedImage: !!v });
            },
          }),
          el(AttachmentControl, {
            label: 'Media Library image',
            help: 'Select the Clinic figure block, then Replace. This is the photograph on the page.',
            allowedTypes: ['image'],
            value: mediaId,
            onSelect: function (media) {
              applyFigureMedia(props, media);
            },
          }),
          el(SelectControl, {
            label: 'Named theme asset',
            value: attrs.name,
            options: [
              { label: 'Custom / none', value: '' },
              { label: 'Baby and parent', value: 'baby-parent' },
              { label: 'Waiting room', value: 'waiting-room' },
              { label: 'Certificates', value: 'certificates' },
              { label: 'Dr Haidar', value: 'dr-haidar' },
              { label: 'Dr Samir', value: 'dr-samir' },
            ],
            onChange: function (v) {
              props.setAttributes({ name: v });
            },
          }),
          el(TextControl, {
            label: 'Alt',
            value: attrs.alt,
            onChange: function (v) {
              props.setAttributes({ alt: v });
            },
          }),
          el(TextControl, {
            label: 'Caption',
            value: attrs.caption,
            onChange: function (v) {
              props.setAttributes({ caption: v });
            },
          })
        )
      ),
      el('div', useBlockProps({ className: 'cil-editor-canvas' }), ssr('cil/figure', previewAttrs))
    );
  }

  var figureEdit = withSelect
    ? withSelect(function (select) {
        var featured = 0;
        try {
          featured = parseInt(select('core/editor').getEditedPostAttribute('featured_media'), 10) || 0;
        } catch (e) {
          featured = 0;
        }
        return { featuredId: featured };
      })(FigureEdit)
    : FigureEdit;

  registerBlockType('cil/figure', {
    apiVersion: 3,
    title: 'Clinic figure',
    category: 'circumcision-london',
    icon: 'format-image',
    supports: { html: false, align: ['wide', 'full'] },
    usesContext: ['postId'],
    attributes: {
      name: { type: 'string', default: '' },
      src: { type: 'string', default: '' },
      webp: { type: 'string', default: '' },
      alt: { type: 'string', default: '' },
      caption: { type: 'string', default: '' },
      ratio: { type: 'string', default: '4-3' },
      width: { type: 'number', default: 1920 },
      height: { type: 'number', default: 1080 },
      imageId: { type: 'number', default: 0 },
      useFeaturedImage: { type: 'boolean', default: false },
    },
    edit: figureEdit,
    save: function () {
      return null;
    },
  });

  dynamicBlock('cil/file-link', 'Document link', {
    icon: 'media-document',
    attributes: {
      fileId: { type: 'number', default: 0 },
      text: { type: 'string', default: '' },
      url: { type: 'string', default: '' },
    },
    inspect: function (props) {
      return el(
        PanelBody,
        { title: 'Document' },
        el(AttachmentControl, {
          label: 'PDF from Media Library',
          help: 'Renders nothing until a PDF or URL is set. Do not upload executable files.',
          allowedTypes: ['application/pdf'],
          value: props.attributes.fileId,
          clearLabel: 'Remove',
          onSelect: function (media) {
            var next = { fileId: media.id || 0 };
            if (media.title && !props.attributes.text) {
              next.text = media.title;
            }
            props.setAttributes(next);
          },
        }),
        el(TextControl, {
          label: 'Link text',
          value: props.attributes.text,
          onChange: function (v) {
            props.setAttributes({ text: v });
          },
        }),
        el(TextControl, {
          label: 'External URL (optional)',
          help: 'Used only when no Media Library PDF is selected.',
          value: props.attributes.url,
          onChange: function (v) {
            props.setAttributes({ url: v });
          },
        })
      );
    },
  });

  dynamicBlock('cil/info-cards', 'Info cards', {
    icon: 'screenoptions',
    attributes: {
      items: { type: 'array', default: [] },
    },
    inspect: function (props) {
      return el(
        PanelBody,
        { title: 'Cards' },
        el(Repeater, {
          items: props.attributes.items,
          blank: { title: '', body: '', html: '' },
          fields: [
            { key: 'title', label: 'Heading' },
            { key: 'body', label: 'Paragraph text (HTML allowed)', rows: 3 },
            { key: 'html', label: 'Rich body (lists, used instead of paragraph)', rows: 4 },
          ],
          addLabel: 'Add card',
          onChange: function (next) {
            props.setAttributes({ items: next });
          },
        })
      );
    },
  });
})(window.wp);
