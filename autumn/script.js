window.onload = (() => {
  const grid = {};
  const ringCount = 33;

  // Hex SVG
  const svg = `
  <svg class="hex-svg" fill="#310E08" viewbox="0 0 200 174">
      <path class="hex-border" d="M0 86.60254037844386L50 0L150 0L200 86.60254037844386L150 173.20508075688772L50 173.20508075688772Z">
    </path>
  </svg>
`;

  function createHex({ className = "hex-not-yet", ring, ...style }) {
    const hexNode = document.createElement("div");

    hexNode.classList.add("hex");
    hexNode.classList.add(className);
    hexNode.classList.add(`hex-ring-${ring}`);
    hexNode.innerHTML = svg;

    Object.keys(style).forEach(rule => (hexNode.style[rule] = style[rule]));

    return hexNode;
  }

  function addHexes() {
    const fragment = document.createDocumentFragment();

    // directional motion values
    const down = { x: 0, y: 28 };
    const up = { x: 0, y: -28 };
    const upAndRight = { x: 24, y: -14 };
    const upAndLeft = { x: -24, y: -14 };

    // ring level 0
    fragment.appendChild(createHex({ className: "hex-center", ring: 0 }));

    // concentric rings
    for (let ring = 1; ring < ringCount; ring++) {
      // will traverse only half the hexagon (/2)
      for (let hexagon = 0, prevHexagon; hexagon <= (ring * 6) / 2; hexagon++) {
        const styles = {};
        styles.ring = ring;

        if (hexagon === 0) {
          // first hexagon

          const pos = { x: 0, y: ring * down.y };
          prevHexagon = pos;

          styles.transform = `translate(${pos.x}px, ${pos.y}px)`;
          styles.className = "hex-bottom";
          fragment.appendChild(createHex(styles));
        } else if (hexagon === (ring * 6) / 2) {
          // last traversed hexagon

          const pos = { x: 0, y: ring * up.y };
          prevHexagon = pos;

          styles.transform = `translate(${pos.x}px, ${pos.y}px)`;
          styles.className = "hex-top";
          fragment.appendChild(createHex(styles));
        } else if (hexagon <= ring) {
          // first sixth of hexagon

          const pos = {
            x: prevHexagon.x + upAndRight.x,
            y: prevHexagon.y + upAndRight.y
          };
          prevHexagon = pos;

          const mirrorStyles = { ...styles };

          styles.className = "hex-bottom-right";
          mirrorStyles.className = "hex-bottom-left";

          styles.transform = `translate(${pos.x}px, ${pos.y}px)`;
          mirrorStyles.transform = `translate(-${pos.x}px, ${pos.y}px)`;
          fragment.appendChild(createHex(styles));
          fragment.appendChild(createHex(mirrorStyles));
        } else if (hexagon <= ring * 2) {
          // second sixth of hexagon
          const mirrorStyles = { ...styles };

          const pos = {
            x: prevHexagon.x + up.x,
            y: prevHexagon.y + up.y
          };
          prevHexagon = pos;

          if (hexagon < ring * 2) {
            styles.className = "hex-right";
            mirrorStyles.className = "hex-left";
          } else {
            styles.className = "hex-top-right";
            mirrorStyles.className = "hex-top-left";
          }

          styles.transform = `translate(${pos.x}px, ${pos.y}px)`;
          mirrorStyles.transform = `translate(${-1 * pos.x}px, ${pos.y}px)`;
          fragment.appendChild(createHex(styles));
          fragment.appendChild(createHex(mirrorStyles));
        } else {
          // third sixth of hexagon

          const pos = {
            x: prevHexagon.x + upAndLeft.x,
            y: prevHexagon.y + upAndLeft.y
          };
          prevHexagon = pos;

          // optimization
          const mirrorStyles = { ...styles };

          styles.transform = `translate(${pos.x}px, ${pos.y}px)`;
          styles.className = "hex-top-right";
          mirrorStyles.transform = `translate(${-1 * pos.x}px, ${pos.y}px)`;
          mirrorStyles.className = "hex-top-left";
          fragment.appendChild(createHex(styles));
          fragment.appendChild(createHex(mirrorStyles));
        }
      }
    }

    grid.node.appendChild(fragment);
  }

  let currentColor = "#FFC434";
  const rainbowColors = ["#582345", "#902B3E", "#C73639", "#FD5632", "#FFC434"];

  const getNextRainbowColor = () => {
    const currentIndex = rainbowColors.findIndex(c => c === currentColor);
    currentColor = rainbowColors[(currentIndex + 1) % 5];
    return currentColor;
  };

  function colorLoop(selector) {
    return gsap
      .timeline({ yoyo: true, repeat: -1 })
      .to(selector, {
        duration: 0.5,
        fill: getNextRainbowColor(),
        ease: "power2.easeOut",
        stagger: {
          amount: 1,
          each: 0.0625,
          from: "random"
        }
      })
      .to(
        selector,
        {
          duration: 0.5,
          fill: getNextRainbowColor(),
          ease: "power2.easeOut",
          stagger: {
            amount: 1,
            each: 0.0625,
            from: "random"
          }
        },
        "<0.125"
      );
  }

  function gridSetup() {
    grid.node = document.querySelector(".hero__background");
    grid.rings = ringCount;

    addHexes();

    gsap
      .timeline()
      .add(colorLoop(".hex-ring-0 .hex-svg"), "<0.125")
      .add(colorLoop(".hex-ring-1 .hex-svg"), "<0.125")
      .add(colorLoop(".hex-ring-2 .hex-svg"), "<0.125")
      .add(colorLoop(".hex-ring-3 .hex-svg"), "<0.125")
      .add(colorLoop(".hex-ring-4 .hex-svg"), "<0.125")
      .add(colorLoop(".hex-ring-5 .hex-svg"), "<0.125")
      .add(colorLoop(".hex-ring-6 .hex-svg"), "<0.125")
      .add(colorLoop(".hex-ring-7 .hex-svg"), "<0.125")
      .add(colorLoop(".hex-ring-8 .hex-svg"), "<0.125")
      .add(colorLoop(".hex-ring-9 .hex-svg"), "<0.125")
      .add(colorLoop(".hex-ring-10 .hex-svg"), "<0.125")
      .add(colorLoop(".hex-ring-11 .hex-svg"), "<0.125")
      .add(colorLoop(".hex-ring-12 .hex-svg"), "<0.125")
      .add(colorLoop(".hex-ring-13 .hex-svg"), "<0.125")
      .add(colorLoop(".hex-ring-14 .hex-svg"), "<0.125")
    .add(colorLoop(".hex-ring-15 .hex-svg"), "<0.125")
      .add(colorLoop(".hex-ring-16 .hex-svg"), "<0.125")
      .add(colorLoop(".hex-ring-17 .hex-svg"), "<0.125")
      .add(colorLoop(".hex-ring-18 .hex-svg"), "<0.125")
      .add(colorLoop(".hex-ring-19 .hex-svg"), "<0.125")
      .add(colorLoop(".hex-ring-20 .hex-svg"), "<0.125")
      .add(colorLoop(".hex-ring-21 .hex-svg"), "<0.125")
      .add(colorLoop(".hex-ring-22 .hex-svg"), "<0.125")
      .add(colorLoop(".hex-ring-23 .hex-svg"), "<0.125")
      .add(colorLoop(".hex-ring-24 .hex-svg"), "<0.125")
    .add(colorLoop(".hex-ring-25 .hex-svg"), "<0.125")
      .add(colorLoop(".hex-ring-26 .hex-svg"), "<0.125")
      .add(colorLoop(".hex-ring-27 .hex-svg"), "<0.125")
      .add(colorLoop(".hex-ring-28 .hex-svg"), "<0.125")
      .add(colorLoop(".hex-ring-29 .hex-svg"), "<0.125")
      .add(colorLoop(".hex-ring-30 .hex-svg"), "<0.125")
    .add(colorLoop(".hex-ring-31 .hex-svg"), "<0.125")
      .add(colorLoop(".hex-ring-32 .hex-svg"), "<0.125")
      .add(colorLoop(".hex-ring-33 .hex-svg"), "<0.125");
  }

  gridSetup();
})();