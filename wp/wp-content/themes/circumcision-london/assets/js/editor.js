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
  var RichText = wp.blockEditor.RichText;
  var PanelBody = wp.components.PanelBody;
  var TextControl = wp.components.TextControl;
  var TextareaControl = wp.components.TextareaControl;
  var ToggleControl = wp.components.ToggleControl;
  var SelectControl = wp.components.SelectControl;
  var Button = wp.components.Button;
  var ServerSideRender = wp.serverSideRender;
  var withSelect = wp.data && wp.data.withSelect ? wp.data.withSelect : null;
  var useSelect = wp.data.useSelect;

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

  function edLabel(text) {
    return el('strong', { className: 'cil-ed-label' }, text);
  }

  function setAttr(props, key, value) {
    var next = {};
    next[key] = value;
    props.setAttributes(next);
  }

  function stripTags(value) {
    return String(value || '').replace(/<[^>]*>/g, '');
  }

  function canvasField(props, field) {
    var value = props.attributes[field.key];
    if (field.kind === 'repeater') {
      var items = value && value.length ? value.slice() : field.fallback ? field.fallback() : [];
      return el(
        'div',
        { className: 'cil-ed-field', key: field.key },
        edLabel(field.label),
        field.help ? el('p', { className: 'cil-ed-help' }, field.help) : null,
        el(Repeater, {
          items: items,
          blank: field.blank || {},
          fields: field.fields,
          addLabel: field.addLabel,
          onChange: function (next) {
            setAttr(props, field.key, next);
          },
        })
      );
    }
    if (field.kind === 'toggle') {
      return el(ToggleControl, {
        key: field.key,
        label: field.label,
        help: field.help,
        checked: !!value,
        onChange: function (next) {
          setAttr(props, field.key, !!next);
        },
      });
    }
    if (field.kind === 'select') {
      return el(SelectControl, {
        key: field.key,
        label: field.label,
        help: field.help,
        value: value === undefined || value === null ? '' : String(value),
        options: field.options || [],
        onChange: function (next) {
          setAttr(props, field.key, field.numeric ? parseInt(next, 10) : next);
        },
      });
    }
    if (field.kind === 'markup') {
      return el(
        'div',
        { className: 'cil-ed-field', key: field.key },
        edLabel(field.label),
        field.help ? el('p', { className: 'cil-ed-help' }, field.help) : null,
        el(TextareaControl, {
          value: value || '',
          rows: field.rows || 4,
          onChange: function (next) {
            setAttr(props, field.key, next);
          },
        })
      );
    }
    if (field.kind === 'image' || field.kind === 'video' || field.kind === 'file') {
      return el(
        'div',
        { className: 'cil-ed-field cil-ed-media', key: field.key },
        el(AttachmentControl, {
          label: field.label,
          help: field.help,
          allowedTypes: field.kind === 'video' ? ['video'] : field.kind === 'file' ? ['application/pdf'] : ['image'],
          value: value,
          onSelect: function (media) {
            setAttr(props, field.key, media.id || 0);
          },
        })
      );
    }
    if (field.kind === 'html') {
      return el(
        'div',
        { className: 'cil-ed-field', key: field.key },
        edLabel(field.label),
        field.help ? el('p', { className: 'cil-ed-help' }, field.help) : null,
        el(RichText, {
          tagName: field.tagName || 'div',
          className: field.className || 'body-text',
          value: value || '',
          allowedFormats: ['core/bold', 'core/italic', 'core/link'],
          placeholder: field.placeholder || 'Write…',
          onChange: function (v) {
            setAttr(props, field.key, v);
          },
        })
      );
    }
    return el(
      'div',
      { className: 'cil-ed-field', key: field.key },
      edLabel(field.label),
      el(RichText, {
        tagName: field.tagName || 'p',
        className: field.className || '',
        value: value || '',
        allowedFormats: [],
        placeholder: field.placeholder || '',
        onChange: function (v) {
          setAttr(props, field.key, stripTags(v));
        },
      })
    );
  }

  function canvasFields(props, fields) {
    return el(
      'div',
      { className: 'cil-ed-stack' },
      (fields || []).map(function (field) {
        return canvasField(props, field);
      })
    );
  }

  function Repeater(props) {
    var items = props.items && props.items.length ? props.items.slice() : [];
    var blank = props.blank || {};

    function update(i, key, value) {
      var next = items.slice();
      var patch = {};
      patch[key] = value;
      next[i] = Object.assign({}, next[i] || {}, patch);
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

    function move(i, direction) {
      var next = items.slice();
      var target = i + direction;
      if (target < 0 || target >= next.length) {
        return;
      }
      var moved = next.splice(i, 1)[0];
      next.splice(target, 0, moved);
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
            if (field.kind === 'toggle') {
              return el(ToggleControl, {
                key: field.key,
                label: field.label,
                checked: !!item[field.key],
                onChange: function (v) {
                  update(i, field.key, v);
                },
              });
            }
            if (field.kind === 'image-url') {
              return el(
                'div',
                { key: field.key },
                el(TextControl, {
                  label: field.label,
                  help: field.help,
                  value: item[field.key] || '',
                  onChange: function (v) {
                    update(i, field.key, v);
                  },
                }),
                el(
                  MediaUploadCheck,
                  {},
                  el(MediaUpload, {
                    onSelect: function (media) {
                      if (media && media.url) {
                        update(i, field.key, media.url);
                      }
                    },
                    allowedTypes: ['image'],
                    render: function (obj) {
                      return el(Button, { variant: 'secondary', onClick: obj.open }, 'Choose a photo from the Media Library');
                    },
                  })
                )
              );
            }
            var Control = field.rows ? TextareaControl : TextControl;
            return el(Control, {
              key: field.key,
              label: field.label,
              help: field.help,
              value: item[field.key] || '',
              onChange: function (v) {
                update(i, field.key, v);
              },
              rows: field.rows || undefined,
            });
          }),
          el(
            'div',
            { style: { display: 'flex', gap: '12px', flexWrap: 'wrap' } },
            el(Button, { isLink: true, disabled: i === 0, onClick: function () { move(i, -1); } }, 'Move up'),
            el(Button, { isLink: true, disabled: i === items.length - 1, onClick: function () { move(i, 1); } }, 'Move down'),
            el(Button, { isLink: true, isDestructive: true, onClick: function () { remove(i); } }, 'Remove')
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
        function instruction() {
          return extra.instruction ? el('p', { className: 'cil-ed-instruction' }, extra.instruction) : null;
        }
        function description() {
          return extra.description ? el('p', { className: 'cil-ed-help' }, extra.description) : null;
        }
        function fieldStack() {
          return el('div', { className: 'cil-ed-stack' }, instruction(), description(), canvasFields(props, extra.fields));
        }
        var inspect = extra.inspect ? extra.inspect(props) : null;
        var sidebar = null;
        var canvas;
        if (extra.shared && extra.fields) {
          sidebar = el(
            InspectorControls,
            {},
            el(PanelBody, { title: extra.panelTitle || 'Section content', initialOpen: true }, fieldStack())
          );
          canvas = el('div', { className: 'cil-editor-fields' }, fieldStack());
        } else if (extra.mirrorInspect && inspect) {
          sidebar = el(InspectorControls, {}, inspect);
          canvas = el('div', { className: 'cil-editor-fields' }, instruction(), description(), extra.inspect(props));
        } else {
          sidebar = inspect ? el(InspectorControls, {}, inspect) : null;
          canvas = extra.fields ? canvasFields(props, extra.fields) : ssr(name, props.attributes);
        }
        return el(
          Fragment,
          {},
          sidebar,
          el('div', useBlockProps({ className: 'cil-editor-canvas' }), canvas)
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

  /* Words inside a layout section stay in their own blocks. The sidebar
   * edits those blocks directly, so there is one copy of each sentence. */
  var figureChoices = [
    { label: 'Custom / none', value: '' },
    { label: 'Baby and parent', value: 'baby-parent' },
    { label: 'Waiting room', value: 'waiting-room' },
    { label: 'Certificates', value: 'certificates' },
    { label: 'Dr Haidar', value: 'dr-haidar' },
    { label: 'Dr Samir', value: 'dr-samir' },
    { label: 'Clinic exterior', value: 'clinic-exterior' },
    { label: 'Treatment room', value: 'treatment-room' },
    { label: 'Sterilisation', value: 'sterilisation' },
    { label: 'Smiling baby', value: 'baby-smiling' },
  ];

  function writeBlock(clientId, key, value) {
    var patch = {};
    patch[key] = value;
    wp.data.dispatch('core/block-editor').updateBlockAttributes(clientId, patch);
  }

  function wordControl(clientId, key, label, value, help, rows) {
    var Control = rows ? TextareaControl : TextControl;
    return el(Control, {
      key: clientId + ':' + key,
      label: label,
      help: help || '',
      value: value == null ? '' : String(value),
      rows: rows || undefined,
      onChange: function (next) {
        writeBlock(clientId, key, next);
      },
    });
  }

  function flattenBlocks(blocks, parent, acc) {
    (blocks || []).forEach(function (block) {
      acc.push({ block: block, parent: parent });
      if (block.innerBlocks && block.innerBlocks.length) {
        flattenBlocks(block.innerBlocks, block, acc);
      }
    });
    return acc;
  }

  function SectionWords(props) {
    var blocks = useSelect(
      function (select) {
        return select('core/block-editor').getBlocks(props.clientId);
      },
      [props.clientId]
    );
    var flat = flattenBlocks(blocks, null, []);
    var controls = [];
    var paragraphCount = 0;
    var paragraphTotal = flat.filter(function (row) {
      return row.block.name === 'core/paragraph';
    }).length;
    var listCount = 0;
    var buttonCount = 0;
    var sawHeading = false;

    flat.forEach(function (row) {
      var block = row.block;
      var attrs = block.attributes || {};
      var id = block.clientId;

      if (block.name === 'cil/rich-html') {
        var html = attrs.html || '';
        var eyebrow = html.match(/^<span class="caps eyebrow">([\s\S]*)<\/span>$/);
        if (eyebrow) {
          controls.push(
            el(TextControl, {
              key: id + ':eyebrow',
              label: 'Small heading above the title',
              help: 'The short line above the main text.',
              value: eyebrow[1],
              onChange: function (next) {
                writeBlock(id, 'html', '<span class="caps eyebrow">' + next + '</span>');
              },
            })
          );
        } else if (html) {
          controls.push(wordControl(id, 'html', 'Text', html, 'Keep any existing styling in this box.', 3));
        }
        return;
      }

      if (block.name === 'cil/section-head') {
        if (attrs.eyebrow) {
          controls.push(wordControl(id, 'eyebrow', 'Small heading above the title', attrs.eyebrow, '', 0));
        }
        if (attrs.heading) {
          sawHeading = true;
          controls.push(wordControl(id, 'heading', 'Main heading', attrs.heading, '', 2));
        }
        if (attrs.lede) {
          controls.push(wordControl(id, 'lede', 'Text under the heading', attrs.lede, 'Keep any links unless you mean to change them.', 3));
        }
        return;
      }

      if (block.name === 'core/heading' && attrs.content) {
        sawHeading = true;
        controls.push(wordControl(id, 'content', 'Main heading', attrs.content, '', 0));
        return;
      }

      if (block.name === 'core/paragraph') {
        paragraphCount += 1;
        var paragraphLabel = paragraphTotal === 1 && sawHeading ? 'Text under the heading' : 'Paragraph ' + paragraphCount;
        var paragraphHelp = String(attrs.content || '').indexOf('<') !== -1 ? 'Keep any links unless you mean to change them.' : '';
        controls.push(wordControl(id, 'content', paragraphLabel, attrs.content, paragraphHelp, 4));
        return;
      }

      if (block.name === 'core/list-item') {
        listCount += 1;
        var listClass = row.parent && row.parent.attributes ? row.parent.attributes.className || '' : '';
        var listLabel = listClass.indexOf('cil-checklist') !== -1 ? 'Checklist line ' + listCount : 'List item ' + listCount;
        controls.push(wordControl(id, 'content', listLabel, attrs.content, '', 2));
        return;
      }

      if (block.name === 'core/button') {
        buttonCount += 1;
        var buttonPrefix = buttonCount > 1 ? 'Button ' + buttonCount + ' ' : 'Button ';
        controls.push(wordControl(id, 'text', buttonPrefix + 'text', attrs.text, '', 0));
        controls.push(wordControl(id, 'url', buttonPrefix + 'destination', attrs.url, '', 0));
        return;
      }

      if (block.name === 'cil/figure') {
        controls.push(
          el(SelectControl, {
            key: id + ':name',
            label: 'Photograph already on the page',
            help: 'Leave this on the current choice to keep the same picture.',
            value: attrs.name || '',
            options: figureChoices,
            onChange: function (next) {
              writeBlock(id, 'name', next);
            },
          })
        );
        controls.push(wordControl(id, 'alt', 'Image description for accessibility', attrs.alt, '', 0));
        if (attrs.caption) {
          controls.push(wordControl(id, 'caption', 'Caption', attrs.caption, '', 0));
        }
        return;
      }

      if (block.name === 'cil/info-cards') {
        var cards = attrs.items || [];
        var cardFields = [{ key: 'title', label: 'Card heading' }];
        if (cards.some(function (card) { return card && card.icon; })) {
          cardFields.unshift({ key: 'icon', label: 'Symbol', help: 'award, users, heart, refresh, map or star. Leave this to keep the current symbol.' });
        }
        if (cards.some(function (card) { return card && card.eyebrow; })) {
          cardFields.push({ key: 'eyebrow', label: 'Small heading' });
        }
        if (cards.some(function (card) { return card && card.body; })) {
          cardFields.push({ key: 'body', label: 'Card text', rows: 3 });
        }
        if (cards.some(function (card) { return card && card.html; })) {
          cardFields.push({
            key: 'html',
            label: 'Card text with links or an address',
            rows: 5,
            help: 'Keep the links and line breaks unless you mean to change them.',
          });
        }
        if (cards.some(function (card) { return card && card.ctaLabel; })) {
          cardFields.push({ key: 'ctaLabel', label: 'Button text' });
          cardFields.push({ key: 'ctaUrl', label: 'Button destination' });
        }
        if (cards.some(function (card) { return card && card.cta2Label; })) {
          cardFields.push({ key: 'cta2Label', label: 'Second button text' });
          cardFields.push({ key: 'cta2Url', label: 'Second button destination' });
        }
        controls.push(
          el('div', { key: id + ':cards' },
            el('p', { className: 'cil-ed-label' }, 'Cards'),
            el(Repeater, {
              items: cards,
              blank: {
                icon: '',
                eyebrow: '',
                title: '',
                body: '',
                html: '',
                ctaLabel: '',
                ctaUrl: '',
                ctaClass: 'btn',
                cta2Label: '',
                cta2Url: '',
                cta2Class: 'btn btn-ghost',
              },
              fields: cardFields,
              addLabel: 'Add card',
              onChange: function (next) {
                writeBlock(id, 'items', next);
              },
            })
          )
        );
        return;
      }

      if (block.name === 'cil/video-grid') {
        (attrs.items || []).forEach(function (item, index) {
          function patchItem(key, value) {
            var next = (attrs.items || []).slice();
            var rowPatch = {};
            rowPatch[key] = value;
            next[index] = Object.assign({}, next[index] || {}, rowPatch);
            writeBlock(id, 'items', next);
          }
          var prefix = (attrs.items || []).length > 1 ? 'Video ' + (index + 1) + ' ' : 'Video ';
          controls.push(el(TextControl, {
            key: id + ':title:' + index,
            label: prefix + 'title',
            value: item.title || '',
            onChange: function (next) { patchItem('title', next); },
          }));
          if (item.description) {
            controls.push(el(TextareaControl, {
              key: id + ':description:' + index,
              label: 'Text under the video',
              value: item.description,
              rows: 3,
              onChange: function (next) { patchItem('description', next); },
            }));
          }
          if (item.duration) {
            controls.push(el(TextControl, {
              key: id + ':duration:' + index,
              label: 'Video length',
              value: item.duration,
              onChange: function (next) { patchItem('duration', next); },
            }));
          }
          if (item.mediaKey) {
            controls.push(el(TextControl, {
              key: id + ':media:' + index,
              label: 'Video file',
              help: 'The video already on the page. Leave it unless the clinic gives you a replacement file path.',
              value: item.mediaKey,
              onChange: function (next) { patchItem('mediaKey', next); },
            }));
          }
          if (item.poster) {
            controls.push(el(TextControl, {
              key: id + ':poster:' + index,
              label: 'Poster image',
              help: 'The still image shown before the video plays. Leave it to keep the current poster.',
              value: item.poster,
              onChange: function (next) { patchItem('poster', next); },
            }));
          }
        });
      }
    });

    if (!controls.length) {
      return el('p', { className: 'cil-ed-help' }, 'This section has no text of its own.');
    }
    return el(Fragment, {}, controls);
  }

  function wordsPanel(clientId) {
    return el(
      PanelBody,
      { title: 'Words in this section', initialOpen: true },
      el('p', { className: 'cil-ed-instruction' }, 'Edit this section — change the fields below, then update the page.'),
      el('p', { className: 'cil-ed-help' }, 'These fields edit the text already in this section. The layout stays the same.'),
      el(SectionWords, { clientId: clientId })
    );
  }

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
          wordsPanel(props.clientId),
          el(
            PanelBody,
            { title: 'Section layout', initialOpen: false },
            el('p', { className: 'cil-ed-help' }, 'Background, width and the in-page link name. The words for this section are in the panel above.'),
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
                { label: 'Sky tint', value: 'bg-sky' },
                { label: 'Navy (white text)', value: 'bg-navy' },
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
              label: 'In-page link name',
              help: 'Other buttons use this to jump to the section. Leave it unless you mean to change one of those links.',
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
      bleed: { type: 'boolean', default: false },
    },
    edit: function (props) {
      var cls = 'split' + (props.attributes.reverse ? ' reverse' : '') + (props.attributes.bleed ? ' split--bleed' : '');
      return el(
        Fragment,
        {},
        el(
          InspectorControls,
          {},
          el(
            PanelBody,
            { title: 'Split' },
            el('p', { className: 'cil-ed-help' }, 'This only changes how the two columns sit. The words and the photograph are edited from the section’s Words panel.'),
            el(ToggleControl, {
              label: 'Reverse on small screens',
              checked: !!props.attributes.reverse,
              onChange: function (v) {
                props.setAttributes({ reverse: v });
              },
            }),
            el(ToggleControl, {
              label: 'Photo bleeds to the screen edge',
              help: 'Wide screens only. Put a Clinic figure in the outer column.',
              checked: !!props.attributes.bleed,
              onChange: function (v) {
                props.setAttributes({ bleed: v });
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
      var cls = 'split' + (props.attributes.reverse ? ' reverse' : '') + (props.attributes.bleed ? ' split--bleed' : '');
      return el('div', useBlockProps.save({ className: cls }), el(InnerBlocks.Content));
    },
  });

  /*
   * Homepage grouping wrappers. They add no markup of their own (saved content
   * is just their inner blocks); without a registration the editor showed them
   * as missing blocks and the sections inside could not be edited.
   */
  [
    ['cil/home-clinic-introduction', 'Homepage: clinic introduction'],
    ['cil/home-meet-practitioners', 'Homepage: meet practitioners'],
    ['cil/home-additional-sections', 'Homepage: additional sections'],
  ].forEach(function (def) {
    registerBlockType(def[0], {
      apiVersion: 3,
      title: def[1],
      category: 'circumcision-london',
      icon: 'screenoptions',
      supports: { html: false, reusable: false },
      edit: function (props) {
        var several = def[0] === 'cil/home-additional-sections';
        return el(
          Fragment,
          {},
          el(
            InspectorControls,
            {},
            several
              ? el(
                  PanelBody,
                  { title: 'Sections in this group', initialOpen: true },
                  el('p', { className: 'cil-ed-help' }, 'This group holds several sections and has no words of its own. Select a section below, then edit its words in the sidebar.')
                )
              : wordsPanel(props.clientId)
          ),
          el(
            'div',
            useBlockProps({ className: 'cil-editor-wrapper' }),
            el(
              'p',
              { className: 'cil-ed-instruction' },
              several
                ? 'Select a section below, then edit its words in the sidebar.'
                : 'Edit this section — change the fields in the sidebar, then update the page.'
            ),
            el(InnerBlocks, { templateLock: false })
          )
        );
      },
      save: function () {
        return el(InnerBlocks.Content);
      },
    });
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

  dynamicBlock('cil/trust-strip', 'Trust strip', {
    icon: 'shield',
    shared: true,
    instruction: 'Edit this section — change the fields below, then update the page.',
    description: 'The four figures shown under the age-group cards, including the Google and CQC links.',
    panelTitle: 'Trust strip',
    attributes: {
      items: { type: 'array', default: [] },
      googleLogo: { type: 'boolean', default: false },
      cqcWidget: { type: 'boolean', default: false },
      googleLinkLabel: { type: 'string', default: '' },
      cqcLinkLabel: { type: 'string', default: '' },
      googleLinkUrl: { type: 'string', default: '' },
      cqcLinkUrl: { type: 'string', default: '' },
    },
    fields: [
      {
        key: 'googleLogo',
        label: 'Show the Google logo and a link to Google Maps',
        kind: 'toggle',
      },
      { key: 'googleLinkLabel', label: 'Google Maps link text' },
      { key: 'googleLinkUrl', label: 'Google Maps link destination' },
      {
        key: 'cqcWidget',
        label: 'Show the CQC link under the CQC figure',
        kind: 'toggle',
      },
      { key: 'cqcLinkLabel', label: 'CQC link text' },
      { key: 'cqcLinkUrl', label: 'CQC link destination' },
      {
        key: 'items',
        label: 'Trust facts',
        kind: 'repeater',
        help: 'Each row is one figure. Only the CQC fact needs the optional link.',
        blank: { n: '', l: '', linkLabel: '', linkUrl: '' },
        fields: [
          { key: 'n', label: 'Figure' },
          { key: 'l', label: 'Text', rows: 3 },
          { key: 'linkLabel', label: 'Link text, if this fact has one' },
          { key: 'linkUrl', label: 'Link destination, if this fact has one' },
        ],
        addLabel: 'Add fact',
      },
    ],
  });

  dynamicBlock('cil/urgent-note', 'Urgent A&E note', { icon: 'warning' });

  dynamicBlock('cil/group-cards', 'Age group cards', {
    icon: 'groups',
    attributes: {
      level: { type: 'number', default: 3 },
      exclude: { type: 'string', default: '' },
      banded: { type: 'boolean', default: false },
      items: { type: 'array', default: [] },
    },
    fields: [
      {
        key: 'items',
        label: 'Age-group cards',
        kind: 'repeater',
        help: 'Leave empty to keep the current theme cards.',
        blank: { title: '', age: '', price: '', blurb: '', cta: '', href: '', image: '', imageAlt: '' },
        fields: [
          { key: 'title', label: 'Title' },
          { key: 'age', label: 'Age line' },
          { key: 'price', label: 'Price' },
          { key: 'blurb', label: 'Description', rows: 3 },
          { key: 'cta', label: 'Link label' },
          { key: 'href', label: 'URL' },
          { key: 'image', label: 'Photo (optional theme image path, e.g. home/children-card-720.webp)' },
          { key: 'imageAlt', label: 'Photo alt text (leave empty if decorative)' },
        ],
        addLabel: 'Add card',
        fallback: function () {
          return window.cilEditorData && cilEditorData.groups ? cilEditorData.groups.slice() : [];
        },
      },
    ],
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
    shared: true,
    instruction: 'Edit this section — change the fields below, then update the page.',
    description: 'The opening banner: heading, short text, the four facts, buttons, poster and video.',
    panelTitle: 'Homepage hero',
    attributes: {
      eyebrow: { type: 'string', default: 'CQC registered · Edgware, North-West London' },
      title: { type: 'string', default: 'Dedicated Circumcision Clinic in London' },
      sub: {
        type: 'string',
        default:
          'Two experienced practitioners who have trained UK and International doctors. Local anaesthetic every time.',
      },
      html: { type: 'string', default: '' },
      facts: { type: 'array', default: [] },
      posterId: { type: 'number', default: 0 },
      videoMp4Id: { type: 'number', default: 0 },
      videoWebmId: { type: 'number', default: 0 },
      ctaLabel: { type: 'string', default: '' },
      ctaUrl: { type: 'string', default: '' },
      phoneLabel: { type: 'string', default: '' },
      phoneUrl: { type: 'string', default: '' },
      showFacts: { type: 'boolean', default: true },
    },
    fields: [
      { key: 'eyebrow', label: 'Small heading above the title' },
      { key: 'title', label: 'Main heading' },
      { key: 'sub', label: 'Text under the heading', kind: 'markup', rows: 3 },
      {
        key: 'html',
        label: 'Text in the right-hand column',
        kind: 'html',
        help: 'Leave this empty. This homepage does not show a column beside the heading.',
      },
      {
        key: 'showFacts',
        label: 'Show the row of facts',
        kind: 'toggle',
        help: 'The four short facts along the bottom of the banner.',
      },
      {
        key: 'facts',
        label: 'Facts',
        kind: 'repeater',
        blank: { text: '', icon: 'star' },
        fields: [
          {
            key: 'text',
            label: 'Fact',
            rows: 2,
            help: 'Words inside <b> and </b> stay bold, as they are on the page now.',
          },
          { key: 'icon', label: 'Symbol (google, shield, clock or pin)' },
        ],
        addLabel: 'Add fact',
      },
      { key: 'ctaLabel', label: 'First button text' },
      { key: 'ctaUrl', label: 'First button destination' },
      { key: 'phoneLabel', label: 'Second button text' },
      { key: 'phoneUrl', label: 'Second button destination' },
      {
        key: 'posterId',
        label: 'Still image behind the video',
        kind: 'image',
        help: 'Leave this unset to keep the clinic photograph already on the page.',
      },
      {
        key: 'videoMp4Id',
        label: 'Hero video',
        kind: 'video',
        help: 'Leave this unset to keep the clinic video already on the page.',
      },
      { key: 'videoWebmId', label: 'Hero video, WebM version', kind: 'video' },
    ],
  });

  dynamicBlock('cil/groups', 'Homepage age groups', {
    icon: 'groups',
    shared: true,
    instruction: 'Edit this section — change the fields below, then update the page.',
    description: 'The three treatment cards for babies, children and adults.',
    panelTitle: 'Age-group cards',
    attributes: {
      level: { type: 'number', default: 2 },
      items: { type: 'array', default: [] },
      facts: { type: 'array', default: [] },
    },
    fields: [
      {
        key: 'level',
        label: 'Heading size',
        kind: 'select',
        numeric: true,
        options: [
          { label: 'Large', value: '2' },
          { label: 'Medium', value: '3' },
        ],
      },
      {
        key: 'items',
        label: 'Cards',
        kind: 'repeater',
        blank: { title: '', age: '', price: '', blurb: '', cta: '', href: '', image: '', imageAlt: '' },
        fields: [
          { key: 'title', label: 'Card heading' },
          { key: 'age', label: 'Age line' },
          { key: 'price', label: 'Price' },
          { key: 'blurb', label: 'Description', rows: 3 },
          { key: 'cta', label: 'Link text' },
          { key: 'href', label: 'Link destination' },
          {
            key: 'image',
            label: 'Photo',
            kind: 'image-url',
            help: 'The photo already used on this card. Leave it unless you are choosing a replacement.',
          },
          { key: 'imageAlt', label: 'Image description for accessibility' },
        ],
        addLabel: 'Add card',
      },
    ],
  });

  dynamicBlock('cil/intro', 'Homepage introduction', {
    icon: 'id',
    shared: true,
    instruction: 'Edit this section — change the fields below, then update the page.',
    description: 'The location section: address, map and the two buttons.',
    panelTitle: 'Location',
    attributes: {
      eyebrow: { type: 'string', default: 'Welcome to the clinic' },
      heading: {
        type: 'string',
        default: 'Choosing circumcision is an important decision. We will talk you through all of it.',
      },
      html: { type: 'string', default: '' },
      imageId: { type: 'number', default: 0 },
      caption: { type: 'string', default: '' },
      mediaType: { type: 'string', default: '' },
      mapEmbedUrl: { type: 'string', default: '' },
      btn1Label: { type: 'string', default: '' },
      btn1Url: { type: 'string', default: '' },
      btn2Label: { type: 'string', default: '' },
      btn2Url: { type: 'string', default: '' },
    },
    fields: [
      { key: 'eyebrow', label: 'Small heading above the title' },
      { key: 'heading', label: 'Main heading' },
      { key: 'html', label: 'Address and directions', kind: 'markup', rows: 6, help: 'Keep the existing link addresses unless you mean to change them.' },
      { key: 'btn1Label', label: 'First button text' },
      { key: 'btn1Url', label: 'First button destination' },
      { key: 'btn2Label', label: 'Second button text' },
      { key: 'btn2Url', label: 'Second button destination' },
      { key: 'caption', label: 'Caption' },
      {
        key: 'mediaType',
        label: 'What to show beside the text',
        kind: 'select',
        options: [
          { label: 'Map', value: 'map' },
          { label: 'Photograph', value: 'image' },
          { label: 'Automatic', value: '' },
        ],
      },
      { key: 'mapEmbedUrl', label: 'Map address', help: 'Leave blank to keep the clinic map already on the page.' },
      { key: 'imageId', label: 'Photograph', kind: 'image', help: 'Used only when a photograph is selected above.' },
    ],
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
          }),
          el(TextControl, {
            label: 'First button text',
            value: props.attributes.btn1Label,
            onChange: function (v) {
              props.setAttributes({ btn1Label: v });
            },
          }),
          el(TextControl, {
            label: 'First button URL',
            value: props.attributes.btn1Url,
            onChange: function (v) {
              props.setAttributes({ btn1Url: v });
            },
          }),
          el(TextControl, {
            label: 'Second button text',
            value: props.attributes.btn2Label,
            onChange: function (v) {
              props.setAttributes({ btn2Label: v });
            },
          }),
          el(TextControl, {
            label: 'Second button URL',
            value: props.attributes.btn2Url,
            onChange: function (v) {
              props.setAttributes({ btn2Url: v });
            },
          })
        ),
        el(
          PanelBody,
          { title: 'Media', initialOpen: true },
          el(TextControl, {
            label: 'Media type',
            help: 'Use “map” for Google Maps, “image” for a photograph. Blank: Location uses map.',
            value: props.attributes.mediaType,
            onChange: function (v) {
              props.setAttributes({ mediaType: v });
            },
          }),
          el(TextareaControl, {
            label: 'Map embed URL',
            help: 'Optional. Leave blank to use the clinic address embed (or Maps Embed API key from Customizer).',
            value: props.attributes.mapEmbedUrl,
            onChange: function (v) {
              props.setAttributes({ mapEmbedUrl: v });
            },
          }),
          el(TextControl, {
            label: 'Caption',
            value: props.attributes.caption,
            onChange: function (v) {
              props.setAttributes({ caption: v });
            },
          }),
          el(AttachmentControl, {
            label: 'Photograph',
            help: 'Used when media type is image.',
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
    shared: true,
    instruction: 'Edit this section — change the fields below, then update the page.',
    description: 'The four next steps.',
    panelTitle: 'Next steps',
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
    fields: [
      { key: 'eyebrow', label: 'Small heading above the title' },
      { key: 'heading', label: 'Main heading' },
      { key: 'lede', label: 'Text under the heading', kind: 'markup', rows: 3 },
      {
        key: 'items',
        label: 'Steps',
        kind: 'repeater',
        blank: { h: '', p: '' },
        fields: [
          { key: 'h', label: 'Step heading' },
          { key: 'p', label: 'Step text', rows: 3 },
        ],
        addLabel: 'Add step',
      },
    ],
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
      cardTitle: { type: 'string', default: '' },
      cardHtml: { type: 'string', default: '' },
      eyebrow: { type: 'string', default: '' },
      heading: { type: 'string', default: '' },
      html: { type: 'string', default: '' },
      urgentTitle: { type: 'string', default: '' },
      urgentBody: { type: 'string', default: '' },
    },
    fields: [
      { key: 'cardEyebrow', label: 'Form eyebrow' },
      { key: 'cardTitle', label: 'Form heading', tagName: 'h2', className: 'display d-2' },
      { key: 'cardHtml', label: 'Form intro box', kind: 'html', help: 'Boxed intro above the form fields.' },
      { key: 'eyebrow', label: 'Copy eyebrow', tagName: 'span', className: 'caps eyebrow' },
      { key: 'heading', label: 'Copy heading', tagName: 'h2', className: 'display d-1' },
      { key: 'html', label: 'Body', kind: 'html', help: 'Boxed “who is suitable” copy on the right.' },
      { key: 'urgentTitle', label: 'Urgent heading', tagName: 'h2', className: 'display d-3' },
      { key: 'urgentBody', label: 'Urgent body', kind: 'html', help: 'Leave blank to keep the current theme urgent note.' },
    ],
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
        el(TextareaControl, {
          label: 'Form intro box HTML',
          help: 'Shown in a box above the form fields.',
          value: props.attributes.cardHtml,
          onChange: function (v) {
            props.setAttributes({ cardHtml: v });
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
          help: 'Boxed “who is suitable” copy on the right.',
          value: props.attributes.html,
          onChange: function (v) {
            props.setAttributes({ html: v });
          },
        }),
        el(TextControl, {
          label: 'Urgent heading',
          value: props.attributes.urgentTitle,
          onChange: function (v) {
            props.setAttributes({ urgentTitle: v });
          },
        }),
        el(TextareaControl, {
          label: 'Urgent body',
          help: 'Leave blank to keep the current theme urgent note.',
          value: props.attributes.urgentBody,
          onChange: function (v) {
            props.setAttributes({ urgentBody: v });
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
    shared: true,
    instruction: 'Edit this section — change the fields below, then update the page.',
    description: 'The review heading, statistic tiles, platform buttons, written quotes and video clips.',
    panelTitle: 'Reviews',
    attributes: {
      eyebrow: { type: 'string', default: '' },
      heading: { type: 'string', default: '' },
      stars: { type: 'string', default: '' },
      stats: { type: 'array', default: [] },
      checkedNote: { type: 'string', default: '' },
      body: { type: 'string', default: '' },
      platforms: { type: 'array', default: [] },
      testimonialsLabel: { type: 'string', default: '' },
      testimonialsUrl: { type: 'string', default: '' },
      videosHeading: { type: 'string', default: '' },
      videos: { type: 'array', default: [] },
      videosCtaLabel: { type: 'string', default: '' },
      videosCtaUrl: { type: 'string', default: '' },
      quotes: { type: 'array', default: [] },
    },
    fields: [
      { key: 'eyebrow', label: 'Small heading above the title' },
      { key: 'heading', label: 'Review heading' },
      { key: 'checkedNote', label: 'Date the reviews were checked', help: 'Leave blank to hide this line.' },
      { key: 'body', label: 'Extra paragraph', help: 'Leave blank to hide this line.' },
      {
        key: 'stats',
        label: 'Statistic tiles',
        kind: 'repeater',
        blank: { n: '', l: '' },
        fields: [
          { key: 'n', label: 'Figure', rows: 2, help: 'Press Enter where the figure breaks onto a second line.' },
          { key: 'l', label: 'Explanation', rows: 2 },
        ],
        addLabel: 'Add tile',
      },
      {
        key: 'platforms',
        label: 'Review websites',
        kind: 'repeater',
        blank: { label: '', url: '', icon: '' },
        fields: [
          { key: 'label', label: 'Button text' },
          { key: 'url', label: 'Button destination' },
          { key: 'icon', label: 'Logo (google, trustpilot or yell)' },
        ],
        addLabel: 'Add website',
      },
      { key: 'testimonialsLabel', label: 'Testimonials button text' },
      { key: 'testimonialsUrl', label: 'Testimonials button destination' },
      { key: 'stars', label: 'Stars shown on each written review' },
      { key: 'videosHeading', label: 'Videos heading' },
      {
        key: 'videos',
        label: 'Video testimonials',
        kind: 'repeater',
        blank: { title: '', mediaKey: '', posterKey: '' },
        fields: [
          { key: 'title', label: 'Video title' },
          {
            key: 'mediaKey',
            label: 'Video file',
            help: 'This is the video already on the page. Leave it unless the clinic gives you a replacement file path.',
          },
          {
            key: 'posterKey',
            label: 'Poster image file',
            help: 'The still image shown before the video plays. Leave it to keep the current poster.',
          },
        ],
        addLabel: 'Add video',
      },
      { key: 'videosCtaLabel', label: 'Videos button text' },
      { key: 'videosCtaUrl', label: 'Videos button destination' },
      {
        key: 'quotes',
        label: 'Written reviews',
        kind: 'repeater',
        blank: { name: '', context: '', quote: '' },
        fields: [
          { key: 'name', label: 'Name' },
          { key: 'context', label: 'Who the review is about' },
          { key: 'quote', label: 'Review', rows: 4 },
        ],
        addLabel: 'Add review',
      },
    ],
    inspect: function (props) {
      return el(
        Fragment,
        {},
        el(
          PanelBody,
          { title: 'Reviews copy' },
          el(TextControl, {
            label: 'Eyebrow',
            value: props.attributes.eyebrow,
            onChange: function (v) {
              props.setAttributes({ eyebrow: v });
            },
          }),
          el(TextareaControl, {
            label: 'Checked-on note',
            help: 'Leave blank to hide (replaced by platform buttons).',
            value: props.attributes.checkedNote,
            onChange: function (v) {
              props.setAttributes({ checkedNote: v });
            },
          }),
          el(TextareaControl, {
            label: 'Body paragraph',
            help: 'Leave blank to hide. Rating figures stay live from clinic data.',
            value: props.attributes.body,
            onChange: function (v) {
              props.setAttributes({ body: v });
            },
          }),
          el(TextControl, {
            label: 'Testimonials button label',
            value: props.attributes.testimonialsLabel,
            onChange: function (v) {
              props.setAttributes({ testimonialsLabel: v });
            },
          }),
          el(TextControl, {
            label: 'Testimonials button URL',
            value: props.attributes.testimonialsUrl,
            onChange: function (v) {
              props.setAttributes({ testimonialsUrl: v });
            },
          })
        ),
        el(
          PanelBody,
          { title: 'Platform buttons', initialOpen: true },
          el(Repeater, {
            items: props.attributes.platforms,
            blank: { label: '', url: '', icon: '' },
            fields: [
              { key: 'label', label: 'Button label' },
              { key: 'url', label: 'URL' },
              { key: 'icon', label: 'Icon (google / trustpilot / yell)' },
            ],
            addLabel: 'Add platform',
            onChange: function (next) {
              props.setAttributes({ platforms: next });
            },
          })
        ),
        el(
          PanelBody,
          { title: 'Video testimonials', initialOpen: false },
          el(TextControl, {
            label: 'Videos heading',
            value: props.attributes.videosHeading,
            onChange: function (v) {
              props.setAttributes({ videosHeading: v });
            },
          }),
          el(Repeater, {
            items: props.attributes.videos,
            blank: { title: '', mediaKey: '', posterKey: '' },
            fields: [
              { key: 'title', label: 'Category / title' },
              { key: 'mediaKey', label: 'Media key' },
              { key: 'posterKey', label: 'Poster key' },
            ],
            addLabel: 'Add video',
            onChange: function (next) {
              props.setAttributes({ videos: next });
            },
          }),
          el(TextControl, {
            label: 'View-all button label',
            value: props.attributes.videosCtaLabel,
            onChange: function (v) {
              props.setAttributes({ videosCtaLabel: v });
            },
          }),
          el(TextControl, {
            label: 'View-all button URL',
            value: props.attributes.videosCtaUrl,
            onChange: function (v) {
              props.setAttributes({ videosCtaUrl: v });
            },
          })
        ),
        el(
          PanelBody,
          { title: 'Written quotes', initialOpen: false },
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
        )
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
      urgentTitle: { type: 'string', default: '' },
      urgentBody: { type: 'string', default: '' },
      formNote: { type: 'string', default: '' },
      nameLabel: { type: 'string', default: '' },
      phoneLabel: { type: 'string', default: '' },
      dobLabel: { type: 'string', default: '' },
      messageLabel: { type: 'string', default: '' },
      optionalLabel: { type: 'string', default: '' },
      consentHtml: { type: 'string', default: '' },
      submitLabel: { type: 'string', default: '' },
      invalidMessage: { type: 'string', default: '' },
      sentMessage: { type: 'string', default: '' },
    },
    fields: [
      { key: 'eyebrow', label: 'Eyebrow', tagName: 'span', className: 'caps eyebrow' },
      { key: 'title', label: 'Heading', tagName: 'h2', className: 'display d-2' },
      { key: 'formNote', label: 'Form introduction', kind: 'html', help: 'Leave blank to keep the standard introduction.' },
      { key: 'nameLabel', label: 'Name field label', placeholder: 'Your name' },
      { key: 'phoneLabel', label: 'Phone field label', placeholder: 'Phone number' },
      { key: 'dobLabel', label: 'Date of birth field label', placeholder: 'Patient DOB' },
      { key: 'messageLabel', label: 'Message field label', placeholder: 'Anything you would like us to know' },
      { key: 'optionalLabel', label: 'Optional marker', placeholder: '(optional)' },
      { key: 'consentHtml', label: 'Consent text', kind: 'html', tagName: 'p', className: '', help: 'Leave blank to keep the standard consent wording.' },
      { key: 'submitLabel', label: 'Button text', placeholder: 'Request a call back' },
      { key: 'invalidMessage', label: 'Message when a field is missing', placeholder: 'Please complete the highlighted field.' },
      { key: 'sentMessage', label: 'Message after sending', placeholder: 'Opening your email app so this reaches the clinic. If nothing happens, call 020 8951 3794.' },
      { key: 'urgentTitle', label: 'Urgent heading', tagName: 'h2', className: 'display d-3' },
      { key: 'urgentBody', label: 'Urgent body', kind: 'html', help: 'Leave blank to keep the current theme urgent note.' },
    ],
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
        }),
        el(TextControl, {
          label: 'Urgent heading',
          value: props.attributes.urgentTitle,
          onChange: function (v) {
            props.setAttributes({ urgentTitle: v });
          },
        }),
        el(TextareaControl, {
          label: 'Urgent body',
          help: 'Leave blank to keep the current theme urgent note.',
          value: props.attributes.urgentBody,
          onChange: function (v) {
            props.setAttributes({ urgentBody: v });
          },
        })
      );
    },
  });

  dynamicBlock('cil/cta-band', 'Talk to us band', {
    icon: 'megaphone',
    shared: true,
    instruction: 'Edit this section — change the fields below, then update the page.',
    description: 'The closing band: the message on the left, the two buttons, and the callback form.',
    panelTitle: 'Talk to us',
    attributes: {
      title: { type: 'string', default: 'Ask us anything before you decide' },
      text: {
        type: 'string',
        default:
          'Most people call with a question rather than to book. That is what the phone is for, and nothing is booked until you say so.',
      },
      eyebrow: { type: 'string', default: '' },
      ctaLabel: { type: 'string', default: '' },
      ctaUrl: { type: 'string', default: '' },
      phoneLabel: { type: 'string', default: '' },
      phoneUrl: { type: 'string', default: '' },
      cardTitle: { type: 'string', default: '' },
      cardHtml: { type: 'string', default: '' },
      cardEyebrow: { type: 'string', default: '' },
      showCallback: { type: 'boolean', default: false },
      formId: { type: 'string', default: 'home' },
      formSubject: { type: 'string', default: 'homepage callback' },
      callbackAnchor: { type: 'string', default: 'request-callback' },
      nameLabel: { type: 'string', default: '' },
      phoneFieldLabel: { type: 'string', default: '' },
      dobLabel: { type: 'string', default: '' },
      messageLabel: { type: 'string', default: '' },
      optionalLabel: { type: 'string', default: '' },
      consentHtml: { type: 'string', default: '' },
      submitLabel: { type: 'string', default: '' },
      invalidMessage: { type: 'string', default: '' },
      sentMessage: { type: 'string', default: '' },
    },
    fields: [
      { key: 'eyebrow', label: 'Small heading above the title' },
      { key: 'title', label: 'Main heading' },
      { key: 'text', label: 'Text under the heading', kind: 'markup', rows: 6, help: 'Keep the telephone, email and WhatsApp links unless you mean to change them.' },
      { key: 'ctaLabel', label: 'First button text' },
      { key: 'ctaUrl', label: 'First button destination' },
      { key: 'phoneLabel', label: 'Second button text' },
      { key: 'phoneUrl', label: 'Second button destination' },
      { key: 'showCallback', label: 'Show the callback form', kind: 'toggle' },
      { key: 'cardEyebrow', label: 'Form heading' },
      { key: 'cardTitle', label: 'Second form heading' },
      { key: 'cardHtml', label: 'Text above the form', kind: 'html' },
      { key: 'nameLabel', label: 'Name field label' },
      { key: 'phoneFieldLabel', label: 'Phone field label' },
      { key: 'dobLabel', label: 'Date of birth field label' },
      { key: 'messageLabel', label: 'Message field label' },
      { key: 'optionalLabel', label: 'Optional marker' },
      { key: 'consentHtml', label: 'Consent wording', kind: 'markup', rows: 4, help: 'Keep the privacy-notice link unless you mean to change it.' },
      { key: 'submitLabel', label: 'Form button text' },
      { key: 'invalidMessage', label: 'Message when a field is missing' },
      { key: 'sentMessage', label: 'Message after the form is sent' },
      { key: 'callbackAnchor', label: 'In-page link name', help: 'Used by the “call you back” link. Leave this as request-callback.' },
      { key: 'formId', label: 'Form reference', help: 'Leave this as home.' },
      { key: 'formSubject', label: 'Email subject line' },
    ],
    inspect: function (props) {
      var ToggleControl = wp.components.ToggleControl;
      return el(
        Fragment,
        {},
        el(
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
            label: 'Lede HTML',
            help: 'Supports links (mailto, tel, WhatsApp, #request-callback).',
            value: props.attributes.text,
            onChange: function (v) {
              props.setAttributes({ text: v });
            },
          }),
          el(TextControl, {
            label: 'Book button text',
            value: props.attributes.ctaLabel,
            onChange: function (v) {
              props.setAttributes({ ctaLabel: v });
            },
          }),
          el(TextControl, {
            label: 'Book button URL',
            value: props.attributes.ctaUrl,
            onChange: function (v) {
              props.setAttributes({ ctaUrl: v });
            },
          })
        ),
        el(
          PanelBody,
          { title: 'Right card', initialOpen: true },
          el(ToggleControl, {
            label: 'Show callback form (instead of WhatsApp card)',
            checked: !!props.attributes.showCallback,
            onChange: function (v) {
              props.setAttributes({ showCallback: !!v });
            },
          }),
          el(TextControl, {
            label: 'Callback card eyebrow',
            value: props.attributes.cardEyebrow,
            onChange: function (v) {
              props.setAttributes({ cardEyebrow: v });
            },
          }),
          el(TextControl, {
            label: 'Card heading',
            value: props.attributes.cardTitle,
            onChange: function (v) {
              props.setAttributes({ cardTitle: v });
            },
          }),
          el(TextareaControl, {
            label: 'Card body / form intro HTML',
            value: props.attributes.cardHtml,
            onChange: function (v) {
              props.setAttributes({ cardHtml: v });
            },
          }),
          el(TextControl, {
            label: 'Callback anchor id',
            value: props.attributes.callbackAnchor,
            onChange: function (v) {
              props.setAttributes({ callbackAnchor: v });
            },
          }),
          el(TextControl, {
            label: 'Form id',
            value: props.attributes.formId,
            onChange: function (v) {
              props.setAttributes({ formId: v });
            },
          }),
          el(TextControl, {
            label: 'Form subject',
            value: props.attributes.formSubject,
            onChange: function (v) {
              props.setAttributes({ formSubject: v });
            },
          })
        )
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
      reviewedLabel: { type: 'string', default: '' },
      homeLabel: { type: 'string', default: '' },
      crumbs: { type: 'array', default: [] },
    },
    fields: [
      { key: 'eyebrow', label: 'Eyebrow', tagName: 'span', className: 'caps eyebrow' },
      { key: 'title', label: 'Title', tagName: 'h1', className: 'display d-1' },
      { key: 'lede', label: 'Lede', kind: 'html', tagName: 'p', className: 'lede' },
      { key: 'reviewedLabel', label: 'Reviewed-by wording', placeholder: 'Clinically reviewed by' },
      { key: 'reviewedBy', label: 'Reviewed by' },
      { key: 'homeLabel', label: 'First breadcrumb label', placeholder: 'Home' },
      {
        key: 'crumbs',
        label: 'Breadcrumbs (after Home)',
        kind: 'repeater',
        blank: { label: '', href: '' },
        fields: [
          { key: 'label', label: 'Label' },
          { key: 'href', label: 'URL (blank on last crumb)' },
        ],
        addLabel: 'Add crumb',
      },
    ],
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
    shared: true,
    instruction: 'Edit this section — change the fields below, then update the page.',
    description: 'The heading for this part of the page. Paragraphs underneath are edited by clicking them.',
    panelTitle: 'Section heading',
    attributes: {
      eyebrow: { type: 'string', default: '' },
      heading: { type: 'string', default: '' },
      lede: { type: 'string', default: '' },
      display: { type: 'string', default: 'd-2' },
    },
    fields: [
      { key: 'eyebrow', label: 'Small heading above the title' },
      { key: 'heading', label: 'Main heading' },
      { key: 'lede', label: 'Text under the heading', kind: 'markup', rows: 3 },
      {
        key: 'display',
        label: 'Heading size',
        kind: 'select',
        options: [
          { label: 'Large', value: 'd-1' },
          { label: 'Medium', value: 'd-2' },
          { label: 'Small', value: 'd-3' },
        ],
      },
    ],
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
    fields: [
      {
        key: 'rows',
        label: 'Specification rows',
        kind: 'repeater',
        blank: { k: '', v: '' },
        fields: [
          { key: 'k', label: 'Label' },
          { key: 'v', label: 'Value', rows: 2 },
        ],
      },
    ],
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
    fields: [
      {
        key: 'items',
        label: 'Steps',
        kind: 'repeater',
        blank: { h: '', p: '' },
        fields: [
          { key: 'h', label: 'Heading' },
          { key: 'p', label: 'Text', rows: 3 },
        ],
        addLabel: 'Add step',
      },
    ],
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
    fields: [
      { key: 'title', label: 'Heading', tagName: 'h2', className: 'display d-2' },
      { key: 'body', label: 'Body', kind: 'html' },
    ],
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
    fields: [
      { key: 'quote', label: 'Quote', kind: 'html', tagName: 'blockquote' },
      { key: 'name', label: 'Name' },
      { key: 'context', label: 'Context' },
    ],
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
      perPage: { type: 'number', default: 0 },
      showStars: { type: 'boolean', default: true },
    },
    inspect: function (props) {
      return el(
        PanelBody,
        { title: 'Quotes' },
        el(ToggleControl, {
          label: 'Show five-star rating on each card',
          help: 'Turn off when the feedback did not come with a star rating.',
          checked: props.attributes.showStars !== false,
          onChange: function (v) {
            props.setAttributes({ showStars: !!v });
          },
        }),
        el(
          'p',
          { style: { fontSize: '13px', color: '#55637a' } },
          'Leave empty to use the three homepage testimonials. Set “Per page” to 10 (or another number) to enable numbered pagination on the front end.'
        ),
        el(TextControl, {
          label: 'Per page (0 = show all)',
          type: 'number',
          value: props.attributes.perPage || 0,
          onChange: function (v) {
            props.setAttributes({ perPage: parseInt(v, 10) || 0 });
          },
        }),
        el(Repeater, {
          items: props.attributes.quotes,
          blank: { name: '', context: '', quote: '', sample: false },
          fields: [
            { key: 'quote', label: 'Quote', rows: 4 },
            { key: 'name', label: 'Name' },
            { key: 'context', label: 'Context' },
            { key: 'sample', label: 'Sample / placeholder (not a real review)', kind: 'toggle' },
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
    shared: true,
    instruction: 'Edit this section — change the fields below, then update the page.',
    description: 'The questions at the bottom of the homepage.',
    panelTitle: 'Questions',
    attributes: {
      heading: { type: 'string', default: 'Questions people ask us' },
      eyebrow: { type: 'string', default: '' },
      items: { type: 'array', default: [] },
    },
    fields: [
      { key: 'eyebrow', label: 'Small heading above the title' },
      { key: 'heading', label: 'Main heading' },
      {
        key: 'items',
        label: 'Questions',
        kind: 'repeater',
        blank: { q: '', a: '' },
        fields: [
          { key: 'q', label: 'Question' },
          { key: 'a', label: 'Answer', rows: 4, help: 'Keep any existing links unless you mean to change them.' },
        ],
        addLabel: 'Add question',
      },
    ],
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
    mirrorInspect: true,
    instruction: 'Edit this section — change the fields below, then update the page.',
    description: 'The practitioner video. The file path keeps the video that is already on the page.',
    attributes: {
      items: { type: 'array', default: [] },
      eyebrow: { type: 'string', default: '' },
      heading: { type: 'string', default: '' },
      lede: { type: 'string', default: '' },
      showEmpty: { type: 'boolean', default: false },
      showHead: { type: 'boolean', default: true },
      layout: { type: 'string', default: 'grid' },
      itemLevel: { type: 'number', default: 3 },
      categoryTitle: { type: 'string', default: '' },
      categoryId: { type: 'string', default: '' },
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
          'Attach browser-playable MP4 (H.264) or WebM files from the Media Library. HEVC/QuickTime .mov files often show 0:00 in Chrome/Edge — convert to H.264 MP4 for playback.'
        ),
        el(TextControl, {
          label: 'Eyebrow (optional)',
          value: props.attributes.eyebrow || '',
          onChange: function (v) {
            props.setAttributes({ eyebrow: v });
          },
        }),
        el(TextControl, {
          label: 'Heading (optional)',
          value: props.attributes.heading || '',
          onChange: function (v) {
            props.setAttributes({ heading: v });
          },
        }),
        el(TextareaControl, {
          label: 'Lede (optional)',
          value: props.attributes.lede || '',
          onChange: function (v) {
            props.setAttributes({ lede: v });
          },
          rows: 3,
        }),
        el(ToggleControl, {
          label: 'Show section heading above grid',
          checked: props.attributes.showHead !== false,
          onChange: function (v) {
            props.setAttributes({ showHead: !!v });
          },
        }),
        el(ToggleControl, {
          label: 'Show empty state when no videos',
          checked: !!props.attributes.showEmpty,
          onChange: function (v) {
            props.setAttributes({ showEmpty: v });
          },
        }),
        el(SelectControl, {
          label: 'Layout',
          help: 'List: one row per video with its title and note beside a poster. Cards: portrait poster tiles in a row. In both, the video loads only when played.',
          value: props.attributes.layout || 'grid',
          options: [
            { label: 'Grid (video testimonials)', value: 'grid' },
            { label: 'List (aftercare videos)', value: 'list' },
            { label: 'Cards (portrait testimonial videos)', value: 'cards' },
          ],
          onChange: function (v) {
            props.setAttributes({ layout: v });
          },
        }),
        props.attributes.layout === 'cards'
          ? el(TextControl, {
              label: 'Category heading (cards layout, optional)',
              help: 'e.g. Baby Testimonials. Shown above the videos only when this block has at least one video.',
              value: props.attributes.categoryTitle || '',
              onChange: function (v) {
                props.setAttributes({ categoryTitle: v });
              },
            })
          : null,
        props.attributes.layout === 'cards'
          ? el(TextControl, {
              label: 'Category anchor ID (optional)',
              help: 'Lowercase letters, numbers and hyphens, e.g. baby-video-testimonials.',
              value: props.attributes.categoryId || '',
              onChange: function (v) {
                props.setAttributes({ categoryId: v.toLowerCase().replace(/[^a-z0-9-]/g, '') });
              },
            })
          : null,
        props.attributes.layout === 'list'
          ? el(SelectControl, {
              label: 'Video title heading level',
              value: String(props.attributes.itemLevel || 3),
              options: [
                { label: 'H2', value: '2' },
                { label: 'H3', value: '3' },
                { label: 'H4', value: '4' },
              ],
              onChange: function (v) {
                props.setAttributes({ itemLevel: parseInt(v, 10) || 3 });
              },
            })
          : null,
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
            el(TextareaControl, {
              label: 'Note (list layout, optional)',
              help: 'Shown under the title. Simple links and bold text are allowed.',
              value: item.description || '',
              rows: 3,
              onChange: function (v) {
                updateItem(i, { description: v });
              },
            }),
            el(TextControl, {
              label: 'Length (list layout, optional, e.g. 2:30)',
              value: item.duration || '',
              onChange: function (v) {
                updateItem(i, { duration: v });
              },
            }),
            el(AttachmentControl, {
              label: 'Poster image',
              allowedTypes: ['image'],
              value: item.posterId,
              clearLabel: 'Remove',
              onSelect: function (media) {
                updateItem(i, { posterId: media.id || 0, poster: media.url || '' });
              },
            }),
            el(AttachmentControl, {
              label: 'MP4',
              allowedTypes: ['video'],
              value: item.mp4Id,
              clearLabel: 'Remove',
              onSelect: function (media) {
                updateItem(i, { mp4Id: media.id || 0, mp4: media.url || '' });
              },
            }),
            el(AttachmentControl, {
              label: 'WebM',
              allowedTypes: ['video'],
              value: item.webmId,
              clearLabel: 'Remove',
              onSelect: function (media) {
                updateItem(i, { webmId: media.id || 0, webm: media.url || '' });
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
              label: 'Poster image file',
              help: 'The still image shown before the video plays. Leave this to keep the current poster.',
              value: item.posterKey || '',
              onChange: function (v) {
                updateItem(i, { posterKey: v.trim() });
              },
            }),
            el(TextControl, {
              label: 'Video file',
              help: 'The video already on the page. Leave this unless the clinic gives you a replacement file path.',
              value: item.mediaKey || '',
              onChange: function (v) {
                updateItem(i, { mediaKey: v.trim() });
              },
            }),
            el(TextControl, {
              label: 'MP4 URL (optional)',
              help: 'Use for videos hosted outside WordPress. Prefer H.264 MP4 for Chrome/Edge.',
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
              'div',
              { style: { display: 'flex', gap: '12px', flexWrap: 'wrap' } },
              el(
                Button,
                {
                  isLink: true,
                  disabled: i === 0,
                  onClick: function () {
                    var next = items.slice();
                    var moved = next.splice(i, 1)[0];
                    next.splice(i - 1, 0, moved);
                    props.setAttributes({ items: next });
                  },
                },
                'Move up'
              ),
              el(
                Button,
                {
                  isLink: true,
                  disabled: i === items.length - 1,
                  onClick: function () {
                    var next = items.slice();
                    var moved = next.splice(i, 1)[0];
                    next.splice(i + 1, 0, moved);
                    props.setAttributes({ items: next });
                  },
                },
                'Move down'
              ),
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
                  { title: '', description: '', duration: '', mediaKey: '', posterKey: '', poster: '', mp4: '', webm: '', posterId: 0, mp4Id: 0, webmId: 0 },
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
    fields: [
      { key: 'eyebrow', label: 'Eyebrow', tagName: 'span', className: 'caps eyebrow' },
      { key: 'heading', label: 'Heading', tagName: 'h2', className: 'display d-2' },
      { key: 'html', label: 'Body', kind: 'html' },
    ],
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
            label: 'Photograph already on the page',
            help: 'Leave this on the current choice to keep the same picture.',
            value: attrs.name,
            options: figureChoices,
            onChange: function (v) {
              props.setAttributes({ name: v });
            },
          }),
          el(TextControl, {
            label: 'Image description for accessibility',
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
      el(
        'div',
        useBlockProps({ className: 'cil-editor-canvas' }),
        el(
          'div',
          { className: 'cil-editor-fields' },
          el('p', { className: 'cil-ed-instruction' }, 'Edit this section — change the fields below, then update the page.'),
          el('p', { className: 'cil-ed-help' }, 'The same photograph, description and caption are in the sidebar.'),
          el(SelectControl, {
            label: 'Photograph already on the page',
            help: 'Leave this on the current choice to keep the same picture.',
            value: attrs.name || '',
            options: figureChoices,
            onChange: function (v) {
              props.setAttributes({ name: v });
            },
          }),
          el(TextControl, {
            label: 'Image description for accessibility',
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
        ),
        ssr('cil/figure', previewAttrs)
      )
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

  dynamicBlock('cil/map-embed', 'Map', {
    icon: 'location',
    attributes: {
      query: { type: 'string', default: '' },
      title: { type: 'string', default: '' },
      address: { type: 'string', default: '' },
      linkUrl: { type: 'string', default: '' },
    },
    inspect: function (props) {
      function text(label, key, help) {
        return el(TextControl, {
          label: label,
          help: help,
          value: props.attributes[key] || '',
          onChange: function (v) {
            var next = {};
            next[key] = v;
            props.setAttributes(next);
          },
        });
      }
      return el(
        PanelBody,
        { title: 'Map' },
        text('Map search (place name and address)', 'query', 'What Google Maps should show, e.g. the clinic name and full address.'),
        text('Map title (screen readers)', 'title'),
        text('Address caption (optional)', 'address'),
        text('"Open in Google Maps" link (optional)', 'linkUrl', 'The clinic’s Google Maps listing URL.')
      );
    },
  });

  dynamicBlock('cil/file-link', 'Document link', {
    icon: 'media-document',
    attributes: {
      fileId: { type: 'number', default: 0 },
      text: { type: 'string', default: '' },
      url: { type: 'string', default: '' },
      mediaKey: { type: 'string', default: '' },
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
        }),
        el(TextControl, {
          label: 'Media store key (optional)',
          help: 'File path in the clinic media store, e.g. aftercare/documents/ring-method-aftercare-sheet.pdf. Used when no PDF or URL is set above.',
          value: props.attributes.mediaKey || '',
          onChange: function (v) {
            props.setAttributes({ mediaKey: v.trim() });
          },
        })
      );
    },
  });

  dynamicBlock('cil/blog-feed', 'Blog feed', {
    icon: 'welcome-write-blog',
    attributes: {
      perPage: { type: 'number', default: 12 },
      moreLabel: { type: 'string', default: 'Read more' },
      category: { type: 'string', default: 'clinic-articles' },
    },
    fields: [
      { key: 'perPage', label: 'Posts to show', kind: 'number' },
      { key: 'moreLabel', label: 'Read more button label' },
      {
        key: 'category',
        label: 'Category slug',
        help: 'Leave as clinic-articles to show clinic blog posts. Edit titles, excerpts and images on each post.',
      },
    ],
  });

  dynamicBlock('cil/info-cards', 'Info cards', {
    icon: 'screenoptions',
    shared: true,
    instruction: 'Edit this section — change the fields below, then update the page.',
    description: 'The cards in this section. Card text keeps its paragraphs and links.',
    panelTitle: 'Cards',
    attributes: {
      items: { type: 'array', default: [] },
      columns: { type: 'string', default: 'g-3' },
      headingLevel: { type: 'number', default: 3 },
    },
    fields: [
      {
        key: 'columns',
        label: 'Cards in each row',
        kind: 'select',
        options: [
          { label: 'Two', value: 'g-2' },
          { label: 'Three', value: 'g-3' },
          { label: 'Four', value: 'g-4' },
        ],
      },
      {
        key: 'items',
        label: 'Cards',
        kind: 'repeater',
        blank: {
          icon: '',
          eyebrow: '',
          title: '',
          body: '',
          html: '',
          ctaLabel: '',
          ctaUrl: '',
          ctaClass: 'btn',
          cta2Label: '',
          cta2Url: '',
          cta2Class: 'btn btn-ghost',
        },
        fields: [
          { key: 'icon', label: 'Symbol (award, users, heart, refresh, map, star)' },
          { key: 'eyebrow', label: 'Small heading' },
          { key: 'title', label: 'Card heading' },
          { key: 'body', label: 'Card text', rows: 3 },
          { key: 'html', label: 'Card text with links or an address', rows: 5, help: 'Use this when the card already contains a link or several lines. Keep those links unless you mean to change them.' },
          { key: 'ctaLabel', label: 'Button text' },
          { key: 'ctaUrl', label: 'Button destination' },
          { key: 'cta2Label', label: 'Second button text' },
          { key: 'cta2Url', label: 'Second button destination' },
        ],
        addLabel: 'Add card',
      },
    ],
    inspect: function (props) {
      return el(
        PanelBody,
        { title: 'Cards' },
        el(SelectControl, {
          label: 'Columns',
          value: props.attributes.columns || 'g-3',
          options: [
            { label: 'Two', value: 'g-2' },
            { label: 'Three', value: 'g-3' },
            { label: 'Four', value: 'g-4' },
          ],
          onChange: function (v) {
            props.setAttributes({ columns: v });
          },
        }),
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

  dynamicBlock('cil/rich-html', 'Rich content', {
    icon: 'editor-paragraph',
    shared: true,
    instruction: 'Edit this section — change the fields below, then update the page.',
    description: 'A short piece of text, such as the line above the clinic introduction.',
    panelTitle: 'Text',
    attributes: {
      html: { type: 'string', default: '' },
    },
    fields: [
      {
        key: 'html',
        label: 'Text',
        kind: 'html',
        help: 'Edit the words. Leave any existing styling in place.',
      },
    ],
  });
})(window.wp);
