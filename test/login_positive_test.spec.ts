import { test, expect } from '@playwright/test';
import * as fs from 'fs';
import * as path from 'path';

// Baca dan parse CSV data login
const csvFilePath = path.resolve(__dirname, './data/login_positive_test_data.csv');
const fileContent = fs.readFileSync(csvFilePath, { encoding: 'utf-8' });

// Pisahkan per baris dan bersihkan whitespace
const lines = fileContent.trim().split(/\r?\n/);
const headers = lines[0].split(',');

// Buat array data credentials
const testData = lines.slice(1).map((line) => {
    const values = line.split(',');
    return {
        username: values[0].trim(),
        password: values[1].trim(),
    };
});

test.describe('Login Positive Tests (Data-Driven from CSV)', () => {
    for (const data of testData) {
        test(`Login valid user: ${data.username}`, async ({ page }) => {
            await page.goto('http://localhost/login');
            await page.getByTestId('input-username').fill(data.username);
            await page.getByTestId('input-password').fill(data.password);
            await page.getByTestId('btn-login').click();

            // Verifikasi notifikasi login sukses dan redirect
            await expect(page.getByTestId('success-message')).toBeVisible();
            await expect(page.getByTestId('welcome-msg')).toBeVisible();
        });
    }
});