/**
 * Partner Directory block editor UI.
 */
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, TextControl, TextareaControl, ToggleControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import ServerSideRender from '@wordpress/server-side-render';

export default function Edit( { attributes, setAttributes } ) {
	const { showHeading, heading, intro } = attributes;

	const blockProps = useBlockProps( {
		className: 'afc-partner-directory-editor',
	} );

	return (
		<>
			<InspectorControls>
				<PanelBody
					title={ __( 'Directory header', 'afc-partner-directory' ) }
					initialOpen={ true }
				>
					<ToggleControl
						label={ __( 'Show section heading', 'afc-partner-directory' ) }
						checked={ showHeading }
						onChange={ ( value ) =>
							setAttributes( { showHeading: value } )
						}
					/>
					<TextControl
						label={ __( 'Heading', 'afc-partner-directory' ) }
						value={ heading }
						onChange={ ( value ) => setAttributes( { heading: value } ) }
						disabled={ ! showHeading }
					/>
					<TextareaControl
						label={ __( 'Introduction', 'afc-partner-directory' ) }
						value={ intro }
						onChange={ ( value ) => setAttributes( { intro: value } ) }
						rows={ 4 }
						disabled={ ! showHeading }
					/>
				</PanelBody>
			</InspectorControls>
			<div { ...blockProps }>
				<ServerSideRender
					block="afc/partner-directory"
					attributes={ attributes }
				/>
			</div>
		</>
	);
}
