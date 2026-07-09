---
name: Editorial Cacao
colors:
  surface: '#131313'
  surface-dim: '#131313'
  surface-bright: '#393939'
  surface-container-lowest: '#0e0e0e'
  surface-container-low: '#1c1b1b'
  surface-container: '#20201f'
  surface-container-high: '#2a2a2a'
  surface-container-highest: '#353535'
  on-surface: '#e5e2e1'
  on-surface-variant: '#d4c3c1'
  inverse-surface: '#e5e2e1'
  inverse-on-surface: '#313030'
  outline: '#9d8d8c'
  outline-variant: '#504443'
  surface-tint: '#eabcb8'
  primary: '#eabcb8'
  on-primary: '#462827'
  primary-container: '#4a2c2a'
  on-primary-container: '#bd928f'
  inverse-primary: '#795553'
  secondary: '#ffb59e'
  on-secondary: '#5e1700'
  secondary-container: '#832c0d'
  on-secondary-container: '#ffa083'
  tertiary: '#ccc6bc'
  on-tertiary: '#333029'
  tertiary-container: '#37342d'
  on-tertiary-container: '#a29c93'
  error: '#ffb4ab'
  on-error: '#690005'
  error-container: '#93000a'
  on-error-container: '#ffdad6'
  primary-fixed: '#ffdad7'
  primary-fixed-dim: '#eabcb8'
  on-primary-fixed: '#2e1413'
  on-primary-fixed-variant: '#5f3e3c'
  secondary-fixed: '#ffdbd0'
  secondary-fixed-dim: '#ffb59e'
  on-secondary-fixed: '#3a0b00'
  on-secondary-fixed-variant: '#802a0b'
  tertiary-fixed: '#e8e2d8'
  tertiary-fixed-dim: '#ccc6bc'
  on-tertiary-fixed: '#1e1b15'
  on-tertiary-fixed-variant: '#4a463f'
  background: '#131313'
  on-background: '#e5e2e1'
  surface-variant: '#353535'
typography:
  headline-xl:
    fontFamily: Libre Caslon Text
    fontSize: 64px
    fontWeight: '400'
    lineHeight: '1.1'
    letterSpacing: -0.02em
  headline-lg:
    fontFamily: Libre Caslon Text
    fontSize: 40px
    fontWeight: '400'
    lineHeight: '1.2'
  headline-md:
    fontFamily: Libre Caslon Text
    fontSize: 32px
    fontWeight: '400'
    lineHeight: '1.3'
  headline-sm:
    fontFamily: Libre Caslon Text
    fontSize: 24px
    fontWeight: '400'
    lineHeight: '1.4'
  body-lg:
    fontFamily: DM Sans
    fontSize: 18px
    fontWeight: '400'
    lineHeight: '1.6'
  body-md:
    fontFamily: DM Sans
    fontSize: 16px
    fontWeight: '400'
    lineHeight: '1.6'
  label-caps:
    fontFamily: DM Sans
    fontSize: 12px
    fontWeight: '700'
    lineHeight: '1'
    letterSpacing: 0.15em
  headline-lg-mobile:
    fontFamily: Libre Caslon Text
    fontSize: 32px
    fontWeight: '400'
    lineHeight: '1.2'
spacing:
  unit: 8px
  section-gap: 120px
  gutter: 24px
  margin-edge: 40px
  container-max: 1280px
---

## Brand & Style

This design system embodies a modern-luxury, craft chocolate aesthetic. It focuses on the intersection of artisanal heritage and editorial minimalism. The visual narrative is "Moody Luxury," moving away from traditional warmth toward a high-contrast, sophisticated atmosphere that mirrors the precise nature of chocolate making.

The style leverages **Minimalism** and **Modernity** with a focus on:
- **Atmospheric Depth:** Utilizing dark, expansive "darkspace" to allow product imagery and typography to breathe.
- **Editorial Precision:** Strict alignment and high-contrast typography typical of premium fashion or high-end culinary journals.
- **Craft Details:** Incorporating subtle iconography as structural dividers to nod to the physical product's geometry.
- **Imagery as Hero:** Photography must be dramatic and high-contrast, treating the product like a museum artifact.

## Colors

The palette is centered on "Cacao Deep," a rich, near-black brown that serves as the foundation for the dark UI. This is complemented by "Parchment Cream" for high-legibility text and "Terracota Sun" as a restrained accent for moments of conversion or emphasis.

- **Primary (Cacao Deep):** Used for primary backgrounds and deep structural layers.
- **Secondary (Terracota Sun):** Reserved for interactive accents, hover states, and key call-to-actions.
- **Tertiary (Parchment Cream):** The primary color for typography and light-mode surfacing.
- **Neutral:** A range of monochromatic blacks and deep greys used to create subtle separation between content sections without breaking the dark-mode immersion.

## Typography

The typography system relies on a high-contrast pairing between a traditional editorial Serif and a modern, utilitarian Sans-Serif.

- **Headlines:** Libre Caslon Text provides an authoritative, literary feel. Use tight tracking for larger sizes to enhance the premium editorial look.
- **Body & Labels:** DM Sans is used for its geometric clarity. It remains unobtrusive, ensuring the narrative flow of the serif headlines remains the focus.
- **Scale:** Large display sizes should be centered for "Welcome" or "Hero" sections to mimic print layout designs. Body copy should maintain generous line height (1.6) to ensure readability against dark backgrounds.

## Layout & Spacing

The layout philosophy follows a **Fixed Grid** model with an emphasis on "Darkspace" (generous negative space on dark backgrounds).

- **Grid:** A 12-column grid for desktop, collapsing to 4 columns for mobile.
- **Rhythm:** Sections are separated by significant vertical gaps (120px+) to create a feeling of unhurried, luxury browsing.
- **Alignment:** Use a mix of centered hero sections and asymmetrical content blocks to create visual interest. Content should often be "containerized" within a maximum width of 1280px to maintain readability on ultra-wide screens.
- **Dividers:** Use the diamond/lozenge marker as a central divider between major thematic shifts in the page.

## Elevation & Depth

This design system avoids traditional drop shadows in favor of **Tonal Layers** and **Backdrop Blurs**.

- **Surfaces:** Depth is created by varying the darkness of the background. Primary content sits on `#1A1A1A`, while secondary containers or cards sit on a slightly lighter `#2A2A2A`.
- **Outlines:** Use thin (1px), low-opacity borders in Parchment Cream (10-15% opacity) to define elements like input fields or card edges without creating heavy visual noise.
- **Overlays:** Use semi-transparent dark overlays on background images to ensure typography maintains a high contrast ratio.

## Shapes

The shape language is strictly **Sharp (0)**. 

To maintain the architectural, editorial feel, avoid rounded corners on all UI elements including buttons, cards, and input fields. The only exceptions are specific brand icons or the diamond markers. This sharp geometry conveys a sense of precision and modern luxury.

## Components

- **Buttons:** Primary buttons are solid Terracota Sun with Parchment Cream text, strictly rectangular. Secondary buttons use a Parchment Cream outline (1px) with no fill.
- **Cards:** Product cards should have no background or border by default; the product image and typography should sit directly on the page background. Use a subtle fill change on hover.
- **Input Fields:** Bottom-border only or thin 1px Ghost borders. Labels use the `label-caps` style sitting above the field.
- **Chips/Badges:** Small, rectangular badges (e.g., "Sold Out") using high-contrast black text on a white or cream background to mimic physical price tags or labels.
- **Dividers:** Horizontal lines should be 1px thin, interrupted in the center by a small lozenge (diamond) icon.
- **Navigation:** Top-tier navigation uses `label-caps` for a clean, structured header.