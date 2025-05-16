import "./bootstrap";
import html2canvas from "html2canvas-pro";
import { jsPDF } from "jspdf";
import * as pdfjsLib from "pdfjs-dist/build/pdf";

const pdfjsWorker = new URL(
  'pdfjs-dist/build/pdf.worker.min.js',
  import.meta.url
).toString();

pdfjsLib.GlobalWorkerOptions.workerSrc = pdfjsWorker;

async function initializePDFJS() {
    try {
        window.pdfjsLib = pdfjsLib;
        window.html2canvas = html2canvas;
        window.jsPDF = jsPDF;
    } catch (error) {
        console.error('Error initializing PDF.js:', error);
    }
}

initializePDFJS();
