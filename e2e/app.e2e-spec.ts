import { EnglishPage } from './app.po';

describe('english App', () => {
  let page: EnglishPage;

  beforeEach(() => {
    page = new EnglishPage();
  });

  it('should display message saying app works', () => {
    page.navigateTo();
    expect(page.getParagraphText()).toEqual('app works!');
  });
});
